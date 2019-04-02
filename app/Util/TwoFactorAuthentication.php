<?php

namespace App\Util;

use App\Jobs\SMS\SendTwoFactorSMS;
use App\Models\User;
use Illuminate\Cache\Repository as CacheRepository;

/**
 * Responsible for generation and management of 2FA tokens,
 * codes and sessions for a user and phone. Accesses the store
 * to manage the validation and sending of 2FA codes via SMS.
 */
class TwoFactorAuthentication
{
    const CACHE_KEY_PATTERN = '2fa.token.%s';
    const DEFAULT_EXPIRY = 5;

    /**
     * The cache provider we'll use to interact
     * with the application cache.
     *
     * @var Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * The user for this 2fa instance.
     *
     * @var App\Models\User
     */
    protected $user;

    /**
     * The phone number to send it to.
     *
     * @var string
     */
    protected $phone;

    /**
     * The expiry in seconds.
     *
     * @var int
     */
    protected $expiry;

    /**
     * Extra metadata to inject into the payload
     * so that we can pass custom data in.
     *
     * @var array
     */
    protected $meta = [];

    private $code;
    private $token;

    /**
     * Create the 2fa instance with a user model.
     *
     * @param Illuminate\Cache\Repository $cache
     * @param App\Models\User|null $user
     */
    public function __construct(CacheRepository $cache)
    {
        $this->cache = $cache;
        $this->expiry = self::DEFAULT_EXPIRY;
    }

    /**
     * Set the expiry time in minutes.
     *
     * @param int $expiry
     * @return TwoFactorAuthentication
     */
    public function setExpiry(int $expiry): TwoFactorAuthentication
    {
        $this->expiry = $expiry;

        return $this;
    }

    /**
     * Set the phone number to send the code to.
     *
     * @param string $phone
     * @return TwoFactorAuthentication
     */
    public function setPhone(string $phone): TwoFactorAuthentication
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Set the user for this auth generation.
     *
     * @param User $user
     * @return TwoFactorAuthentication
     */
    public function setUser(User $user): TwoFactorAuthentication
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set the metadata for the payload.
     *
     * @param array $meta
     */
    public function setMeta(array $meta): TwoFactorAuthentication
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Generate the token to use for cache storage.
     *
     * @return string
     */
    protected function generateToken(): string
    {
        return str_random(32);
    }

    /**
     * Update the values required to send a code, from
     * the data we have stored against a token.
     *
     * @param  string $token
     * @return TwoFactorAuthentication
     */
    public function fromToken(string $token): TwoFactorAuthentication
    {
        $payload = $this->getPayload($token);

        $this->user = User::find(array_get($payload, 'user', false));
        $this->phone = array_get($payload, 'phone', false);
        $this->code = array_get($payload, 'code', false);
        $this->token = array_get($payload, 'token', false);

        return $this;
    }

    /**
     * Using a token, retrieve the User and assign them to this model.
     *
     * @param  string $token
     * @return array
     */
    public function getPayload(string $token): array
    {
        return $this->cache->get($this->key($token), []);
    }

    /**
     * Validates whether or not the provided code is
     * correct.
     *
     * @param  string $token
     * @param  string $code
     * @return bool
     */
    public function validate(string $token, string $code)
    {
        $key = $this->key($token);

        if (!$this->cache->has($key)) {
            return false;
        }

        $payload = $this->cache->get($key, []);

        return $code == array_get($payload, 'code', false);
    }

    /**
     * Get the value of our expiry.
     *
     * @return int
     */
    public function getExpiry(): int
    {
        return $this->expiry;
    }

    /**
     * Finish the 2fa session for a token by
     * removing it from the cache store.
     *
     * @param  string $token
     * @return void
     */
    public function finish(string $token)
    {
        $this->cache->delete($this->key($token));
    }

    /**
     * Send the SMS for 2fa and return the token.
     *
     * @return string
     */
    public function send(): string
    {
        $token = $this->token ?? $this->generateToken();
        $code = $this->code ?? $this->generateCode();

        // We'll cache it.
        $this->cache->put($this->key($token), [
            'user'  => $this->user->getKey(),
            'code'  => $code,
            'phone' => $this->phone,
            'meta'  => $this->meta,
        ], $this->expiry);

        // Trigger the job to send the SMS to the new number.
        SendTwoFactorSMS::dispatch($this->phone, $code);

        return $token;
    }

    /**
     * Generate a string of numbers in a length
     * specified by a config setting.
     *
     * @return string
     */
    private function generateCode()
    {
        $length = (int) config('services.nexmo.code_length', 6);

        // Generated this way to always include the range from 0-9
        // on each digit.
        $digits = [];
        for ($i = 0; $i < $length; $i++) {
            $digits[] = random_int(0, 9);
        }

        return (string) join('', $digits);
    }

    private function key($token)
    {
        return sprintf(self::CACHE_KEY_PATTERN, $token);
    }
}

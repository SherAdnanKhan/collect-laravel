<?php

namespace App\Http\Controllers;

use App\Helpers\Generator;
use App\Models\TmpIntegration;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PPLController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrieveToken(Request $request)
    {
        $nonce = $request->route('nonce');
        $key = TmpIntegration::query()->where(['key' => $nonce]);

        if (!$key->exists()) {
            abort(404);
        }

        $token = Generator::makePPLJWTWithKey($nonce);

        $response = [
            'success' => true,
            'response' => [
                'veva_initiateIpnVerification' => [
                    'resource' => [
                        'token' => $token,
                        '_links' => [
                            'verify' => [
                                'href' => sprintf('%s/api/integration/ppl/verify', env('APP_URL')),
                                'method' => 'POST'
                            ],
                            'cancel' => [
                                'href' => env('FRONTEND_URL')
                            ],
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($response);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $IPN = 0;
        $token = $request->header('Authorization');
        $data = Generator::getDataFromJWT($token, 'data');
        $nonce = Generator::getDataFromJWT($token, 'jti');
        $key = TmpIntegration::query()->where(['key' => $nonce]);

        if (!$key->exists()) {
            abort(404);
        }

        if ($data && !empty($data['IPN']) && !empty($data['IPN'][0]) && !empty($data['IPN'][0]['number'])) {
            $IPN = $data['IPN'][0]['number'];

            try {
                $key->update(['number' => $IPN]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                abort(500, 'Could not update integration PPL number');
            }
        } else {
            abort(404);
        }

        $tmp_data = $key->first();

        $response = [
            'response' => [
                'veva_finalizeIpnVerification' => [
                    'resource' => [
                        '_links' => [
                            'callbackUrl' => [
                                'href' => sprintf('%s/%s?IPN=%s', trim(env('FRONTEND_URL'), '/'), !empty($tmp_data->route) ? trim($tmp_data->route, '/') : '', $IPN)
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($response);
    }
}

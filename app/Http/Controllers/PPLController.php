<?php

namespace App\Http\Controllers;

use App\Helpers\Generator;
use \Illuminate\Http\Request;

class PPLController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function retrieveToken(Request $request)
    {
        $nonce = time();
        $token = Generator::makePPLJWTWithKey($nonce);

        $responce = [
            'success' => true,
            'response' => [
                'veva_initiateIpnVerification' => [
                    'resource' => [
                        'token' => $token,
                        '_links' => [
                            'verify' => [
                                'href' => sprintf('%s/api/integration/ppl/verify', env('APP_API_URL')),
                                'method' => 'POST'
                            ],
                            'cancel' => [
                                'href' => env('APP_URL')
                            ],
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($responce);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request)
    {
        $data = $request->get('data');

        if ($data && !empty($data['IPN']) && !empty($data['IPN'][0]) && !empty($data['IPN'][0]['number'])) {
            $IPN = $data['IPN'][0]['number'];
            dd($IPN);
        }

        $responce = [
            'response' => [
                'veva_finalizeIpnVerification' => [
                    'resource' => [
                        '_links' => [
                            'callbackUrl' => [
                                'href' => env('APP_URL')
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($responce);
    }
}

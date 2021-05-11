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
//        $data = $request->all();
//        $IPN = 0;
//
//        if ($data && !empty($data['IPN']) && !empty($data['IPN'][0]) && !empty($data['IPN'][0]['number'])) {
//            $IPN = $data['IPN'][0]['number'];
//        }
        $IPN = json_encode($request->headers->all());
        $jwt = $request->header('Authorization');

        $response = [
            'response' => [
                'veva_finalizeIpnVerification' => [
                    'resource' => [
                        '_links' => [
                            'callbackUrl' => [
                                'href' => sprintf('%s/?IPN=%s&jwt=%s', trim(env('FRONTEND_URL'), '/'), $IPN, $jwt)
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return response()->json($response);
    }
}

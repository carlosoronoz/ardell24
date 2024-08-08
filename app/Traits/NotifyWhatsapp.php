<?php

namespace App\Traits;

use GuzzleHttp\Client;
use App\Models\Company;
use App\Mail\OrderSuccess;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait NotifyWhatsapp
{
    public function edbNotificationClient($data, $sale)
    {
        try {
            $company = Company::first();
            $client = new Client();
            $client->post("https://graph.facebook.com/$company->graph_version/$company->mobile_id/messages/", [
                'headers' => ['Authorization' => 'Bearer ' . $company->access_token_whatsapp, 'accept' => 'application/json', 'content-type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'messaging_product' => 'whatsapp',
                    'to' => preg_replace('/[(\)\+\-\" "]+/', '', $sale->Payer->phone),
                    'type' => 'template',
                    'template' => [
                        'name' => 'edb_notification_client',
                        'language' => [
                            'code' => 'es'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'document',
                                        'document' => [
                                            'filename' => 'orden.pdf',
                                            'link' => $data['pdf']
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $data['fullname']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['num_document']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['total_amount']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (\Throwable $th) {

            $errorDetails = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ];
            $this->edbError($errorDetails);

            try {

                Mail::to($sale->Payer->email)->send(new OrderSuccess($sale)); //mail client 

            } catch (\Throwable $th) {

                $errorDetails = [
                    'message' => $th->getMessage(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine(),
                    'trace' => $th->getTraceAsString()
                ];
                $this->edbError($errorDetails);
            }
        }
    }

    public function edbNotification($data)
    {
        try {
            $company = Company::first();
            $client = new Client();
            $client->post("https://graph.facebook.com/$company->graph_version/$company->mobile_id/messages/", [
                'headers' => ['Authorization' => 'Bearer ' . $company->access_token_whatsapp, 'accept' => 'application/json', 'content-type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'messaging_product' => 'whatsapp',
                    'to' => preg_replace('/[(\)\+\-\" "]+/', '', $data['phone']),
                    'type' => 'template',
                    'template' => [
                        'name' => 'edb_notification',
                        'language' => [
                            'code' => 'es'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'document',
                                        'document' => [
                                            'filename' => 'orden.pdf',
                                            'link' => $data['pdf']
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $data['fullname']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['num_document']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['total_amount']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['type']
                                    ]
                                ]
                            ],
                            [
                                'type' => 'button',
                                'sub_type' => 'url',
                                'index' => 0,
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $data['url']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (\Throwable $th) {

            $errorDetails = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ];
            $this->edbError($errorDetails);
        }
    }

    public function edbTicketOrder($data)
    {
        try {
            $company = Company::first();
            $client = new Client();
            $client->post("https://graph.facebook.com/$company->graph_version/$company->mobile_id/messages/", [
                'headers' => ['Authorization' => 'Bearer ' . $company->access_token_whatsapp, 'accept' => 'application/json', 'content-type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'messaging_product' => 'whatsapp',
                    'to' => preg_replace('/[(\)\+\-\" "]+/', '', $data['phone']),
                    'type' => 'template',
                    'template' => [
                        'name' => 'edb_ticket_order',
                        'language' => [
                            'code' => 'es'
                        ],
                        'components' => [
                            [
                                'type' => 'header',
                                'parameters' => [
                                    [
                                        'type' => 'document',
                                        'document' => [
                                            'filename' => 'ticket.pdf',
                                            'link' => $data['pdf']
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $data['fullname']
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (\Throwable $th) {

            $errorDetails = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ];
            $this->edbError($errorDetails);
        }
    }

    public function edbError($data)
    {
        try {
            $company = Company::first();
            $client = new Client();
            $client->post("https://graph.facebook.com/$company->graph_version/$company->mobile_id/messages/", [
                'headers' => ['Authorization' => 'Bearer ' . $company->access_token_whatsapp, 'accept' => 'application/json', 'content-type' => 'application/x-www-form-urlencoded'],
                'form_params' => [
                    'messaging_product' => 'whatsapp',
                    'to' => 59897540680,
                    'type' => 'template',
                    'template' => [
                        'name' => 'edb_error',
                        'language' => [
                            'code' => 'es'
                        ],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => [
                                    [
                                        'type' => 'text',
                                        'text' => $data['message']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['file']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $data['line']
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => 'Indefinido.'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        } catch (\Throwable $th) {

            $errorDetails = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'trace' => $th->getTraceAsString()
            ];

            // Convertir el array a JSON
            $errorJson = json_encode($errorDetails);

            // Registrar el error en el log
            Log::debug('Error whatsapp', ['error' => $errorJson]);
        }
    }
}

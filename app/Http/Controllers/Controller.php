<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
     // Método de helper para enviar resposta de erro
     public function sendErrorResponse($message, $status = 400)
     {
         return response()->json([
             'error' => $message
         ], $status);
     }
 
     // Método de helper para sucesso
     public function sendSuccessResponse($data, $message = "Success")
     {
         return response()->json([
             'message' => $message,
             'data' => $data
         ], 200);
     }
}

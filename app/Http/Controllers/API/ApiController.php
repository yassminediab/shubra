<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{

    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($msg = 'Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($msg);
    }

    /**
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotAuthenticated($msg = 'Not Authenticated')
    {
        return $this->setStatusCode(401)->respondWithError($msg);
    }

    /**
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotAcceptable($msg = 'Not Acceptable')
    {
        return $this->setStatusCode(406)->respondWithError($msg);
    }

    /**
     * @param $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($msg)
    {
        return $this->respond(["success" => false, "msg" => $msg]);
    }


    /**
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($msg = "", $data = [])
    {
        return $this->setStatusCode(201)->respondSuccess($msg, $data);
    }

    /**
     * @param $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondAccepted($msg, $data = [])
    {
        return $this->setStatusCode(202)->respondSuccess($msg, $data);
    }

    /**
     * Main Successful response to handle data sent
     * @param string $msg
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess($msg = '', $data = [])
    {
        return $this->respond(["success" => true, "data" => $data, 'msg' => $msg]);
    }
    /**
     * abstract respond method
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * simple 400 Bad request response
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondBadRequest($msg = 'Not Acceptable')
    {
        return $this->setStatusCode(400)->respondWithError($msg);
    }
}

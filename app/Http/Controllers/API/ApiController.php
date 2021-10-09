<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
        return $this->setStatusCode(404)->respondWithError($msg,[],404);
    }

    /**
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotAuthenticated($msg = 'Not Authenticated')
    {
        return $this->setStatusCode(401)->respondWithError($msg,[],401);
    }

    /**
     * @param string $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotAcceptable($msg = 'Not Acceptable', $errors = [])
    {
        return $this->setStatusCode(406)->respondWithError($msg, $errors, 406);
    }

    /**
     * @param $msg
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($msg, $errors = [],$code = 400)
    {
        return $this->respond(["status" => false, "message" => $msg, "error" => $errors, 'data' => [],"code" => $code]);
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
    public function respondSuccess($msg = '', $data = [],$code = 200)
    {
        return $this->respond(["status" => true, "data" => $data, 'message' => $msg, 'error' => [], "code" => $code]);
    }


    public function respondPaginated($message , $data, LengthAwarePaginator $collection)
    {
        $data['nextPageUrl'] = $collection->nextPageUrl();
        $data['currentPage'] = $collection->currentPage();
        $data['total'] = $collection->total();
        $data['hasMorePages'] = $collection->hasMorePages();
        return $this->respondSuccess($message, $data);
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
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondBadRequest($message = 'Not Acceptable', $errors = [])
    {
        return $this->setStatusCode(400)->respondWithError($message,$errors);
    }
}

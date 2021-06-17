<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\ErrorLog;
use Auth;
use Redirect;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        /*if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            if($request->ajax()){
                if ($exception->getMessage()) {
                    $message = $exception->getMessage();
                }else{
                    $message = 'Something wrong try again';
                }
                $notification = array(
                    'message' => 'Something wrong try again',
                    'alert-type' => 'error',
                    'data'=>$message
                );
                return response()->json($notification, 500);
            }else{
                return redirect()->route('login');
            }
        }
        if ($exception->getMessage()) {
                $errorLog = new ErrorLog;
                $errorLog->ip_address = request()->ip();

                if(isset(Auth::user()->id) && !empty(Auth::user()->id)){
                $errorLog->user_id = Auth::user()->id;
                }

                $errorLog->error = json_encode($exception->getMessage());
                $errorLog->save();

                $notification = array(
                    'message' => 'Something wrong try again',
                    'alert-type' => 'error',
                    'data'=>$exception->getMessage()
                );

                if($request->wantsJson()){
                    return response()->json($notification, 500);
                }else{
                    return parent::render($request, $exception);
                    return back()->with($notification);
                }
       }*/

        return parent::render($request, $exception);
    }
}
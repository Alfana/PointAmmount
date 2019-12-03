<?php
namespace App\Traits;

trait Resmod
{
	public $successStatus = 200;

	protected function success($data = [], $message = 'success', $status = 1) {
		$response = [
			'status'  => $status,
			'data'    => $data,
			'message' => $message 
		];

		return response()->json($response, 200);
	}

	protected function failed($data = [], $message = 'error', $status = 0) {
		$response = [
			'status'  => $status,
			'data'    => $data,
			'message' => $message 
		];

		return response()->json($response, 200);
	}
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Exceptions\NotFoundException;
use App\Models\Todo;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date'   => 'date',
                'status' => 'integer',
            ],
        );
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Ошибка валидации данных!',
                ],
                400,
            );
        }

        $qb = Todo::query();

        if ($request->has('date')) {
            $qb->whereDate('created_at', Carbon::parse($request->date));
        }
    
        if ($request->has('status')) {
            $qb->where('status', '=', $request->status);
        }
    
        $todo = $qb->get();
        
        return response()->json(
            [
                'status' => true,
                'todo'   => $todo,
            ],
            200,
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title'   => 'required',
                'content' => 'required',
            ],
        );
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Ошибка валидации данных!',
                ],
                400,
            );
        }

        $todo = Todo::create(
            [
                'status'  => $request->has('status') ? $request->get('status') : 0,
                'title'   => $request->get('title'),
                'content' => $request->get('content'),
            ],
        );

        return response()->json(
            [
                'status'  => true,
                'message' => 'Задача создана!',
                'todo'    => $todo,
            ],
            200,
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo = Todo::find($id);
        if ($todo === null) {
            throw new NotFoundException();
        }

        $validator = Validator::make(
            $request->all(),
            [
                'title'   => 'required',
                'content' => 'required',
                'status'  => 'integer',
            ],
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status'  => false,
                    'message' => 'Ошибка валидации данных!',
                ],
                400,
            );
        }

        $todo->status  = $request->has('status') ? $request->get('status') : $todo->status;
        $todo->title   = $request->get('title');
        $todo->content = $request->get('content');
        $todo->save();

        return response()->json(
            [
                'status'  => true,
                'message' => 'Задача обновлена!',
            ],
            200,
        );
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $todo = Todo::find($id);
        if ($todo === null) {
            throw new NotFoundException();
        }

        Todo::where('id', $id)->delete();
        return response()->json(
            [
                'status' => true,
                'message' => "Задача удалена!",
            ],
            200
        );
    }
}

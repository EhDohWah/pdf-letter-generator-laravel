<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Books;

class BookController extends Controller
{
    //Get
    public function index() {
        $books = Books::all();
        \Log::info($books); // Log the books data
        return response()->json($books);
    }

    //Post
    public function store(Request $request) {
        $book = new Books;
        $book->name = $request->name;
        $book->author = $request->author;
        $book->publish_date = $request->publish_date;
        $book->save();
        return response()->json([
            "message"=>"Book Added."
        ], 201);
    }

    public function show($id) {
        $book = Books::find($id);
        if (!empty($book)){
            return response()->json($book);
        } else {
            return response()->json([
                "message"=>"Book not found"
            ], 404);
        }
    }


    // Update book
    public function update(Request $request, $id) {
        if (Books::where('id', $id)->exists()) {
            $book = Books::find($id);

            $book->name = is_null($request->name) ? $book->name : $request->name;
            $book->author = is_null($request->author) ? $book->author : $request->author;
            $book->publish_date = is_null($request->publish_date) ? $book->publish_date : $request->publish_date;

            $book->save();

            return response()->json([
                "message" => "Book Added"
            ], 404);
        } else {
            return response()->json([
                "message" => "Book Not found."
            ], 404);
        }
    }


    public function destroy($id) {
        if (Books::where('id', $id)->exists()) {
            $book = Books::find($id);
            $book->delete();

            return response()->json([
                "message" => "a record deleted."
            ], 202);
        } else {
            return response()->json([
                "message" => "Book not found."
            ], 404);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BooksExport;
use PDF;

class HomeController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::all();
        $books = Book::paginate(10);
        $data_view = [
            'users' => $users,
            'books' => $books
        ];
        return view('home', $data_view);
    }

    public function registerBook(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|max:500',
            'ISBN' => ['required',
                Rule::unique('books')->where(function($query) use($request){
                    return $query->where('ISBN', "=", $request->ISBN);
                }),
                'numeric','digits:13'
            ],
            'year_publication' => 'required|numeric'
        ],
        [
            'title.required' => 'Please enter your title, to continue with your registration.',
            'title.max' => 'Sorry, the title is too long, please enter a shorter one, maximum 500.',
            'ISBN.digits' => 'Sorry, the ISBN is too long, please enter a shorter one, maximum 13.',
            'ISBN.required' => 'Please enter a correct name, only Alphabetic format can be used.',
            'ISBN.numeric' => 'Please enter a correct ISBN, only Numeric format can be used.',
            'ISBN.unique' => 'The ISBN must be unique.',
            'year_publication.required' => 'Please enter your Year of publication, to continue with your registration.',
            'year_publication.numeric' => 'Please enter a correct year_publication, only Numeric format can be used.',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $book= new Book();
        $book->title=$request->get('title');
        $book->ISBN=$request->get('ISBN');
        $book->year_publication=$request->get('year_publication');
        $book->user_id=$request->get('user_id');
        $book->save();

        return response()->json(['message'=>'Ok']); 
    }
    public function destroyBook(Request $request, $book_id)
    {
        $book = Book::find($book_id);
        $book->delete();
        return redirect('/home'); 
    }

    public function editBook(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|max:500',
            'ISBN' => ['required',
                Rule::unique('books')->where(function($query) use($request){
                    return $query->where('ISBN', "=", $request->ISBN)->where('id', '!=', $request->book_id);
                }),
                'numeric','digits:13'
            ],
            'year_publication' => 'required|numeric'
        ],
        [
            'title.required' => 'Please enter your title, to continue with your registration.',
            'title.max' => 'Sorry, the title is too long, please enter a shorter one, maximum 500.',
            'ISBN.digits' => 'Sorry, the ISBN is too long, please enter a shorter one, maximum 13.',
            'ISBN.required' => 'Please enter a correct name, only Alphabetic format can be used.',
            'ISBN.numeric' => 'Please enter a correct ISBN, only Numeric format can be used.',
            'ISBN.unique' => 'The ISBN must be unique.',
            'year_publication.required' => 'Please enter your Year of publication, to continue with your registration.',
            'year_publication.numeric' => 'Please enter a correct year_publication, only Numeric format can be used.',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }

        $book= Book::find($request->book_id);
        $book->title=$request->get('title');
        $book->ISBN=$request->get('ISBN');
        $book->year_publication=$request->get('year_publication');
        $book->user_id=$request->get('user_id');
        $book->save();

        return response()->json(['message'=>'Ok']); 
    }

    public function export() 
    {
        return Excel::download(new BooksExport, 'books.xlsx');
    }

    public function downloadPDF() 
    {
        $books = Book::paginate(10);
        $users = User::all();
        $data_view = [
            'users' => $users,
            'books' => $books
        ];
        $pdf = PDF::loadView('home', $data_view)->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('books.pdf');
    }
}

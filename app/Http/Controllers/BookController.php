<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');

        //el metodo when se usa con una funcion, cuando se pase un titulo, solo entonces
        //se ejecuta la otra parte de la funcion que busca el titulo sino se eskipea
        $books = Book::when($title, function ($query, $title) {
            return $query->title($title);
        });
            
        $books = match($filter){
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            //por defecto cuando no hay ningun filtro seleccionado, busca primero los ultimos libros creados(latest) 
            //y despues devuelve el valor de average de reviews y el total de reviews de cada libro
            default => $books->latest()->withAvgRating()->withReviewsCount()
        };
        
        // $books = $books->get();
        $cacheKey = 'books:' . $filter . ':' . $title;
        $books = 
            // cache()->remember(
            // $cacheKey, 
            // 3600, 
            // fn() => 
                $books->paginate(10);
            // );

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $cacheKey = 'book:' . $id;

        $book = cache()->remember(
            $cacheKey, 
            3600, 
            fn() => Book::with([ //antes aqui era Book->load porque el modelo ya estaba cargado en la function en show(), ahora que solo se le pasa la id se carga con "with"
                'reviews' => fn($query) => $query->latest()
            ])
                ->withAvgRating()->withReviewsCount()->findOrFail($id) //tambien agregado para que filtre, son todos metodos del Book (modelo)
        );

        return view('books.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

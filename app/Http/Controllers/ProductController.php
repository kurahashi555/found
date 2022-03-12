<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use App\User;
use App\Like;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Product $product)
    {
     return view('Product.index')->with([
      'product' => $product->getPaginateByLimit(),
      ]);
    }
    
    public function display(Product $product)
    {
     return view('Product.display')->with([
      'product' => $product,
      'like' => $like=Like::where('product_id', $product->id)->where('user_id', auth()->user()->id)->first()
      ]);
    }
    
    public function create(Category $category)
    {
     return view('Product.create')->with(['categories' => $category->get()]);;
    }
    
    public function search()
    {
     return view('Product.search');
    }
    
    public function reference(Request $request)
    {
       $keyword_request = $request->input('keyword');//検索に入力されたキーワードを$keyword_requestに挿入
     
     if($keyword_request !== null)
        {
         $query = Product::query();
         $products = $query->where('name','like', '%' .$keyword_request. '%')->get();//productテーブルのnameカラムに$keyword_requestと同じ文字が入っているものを取得
         $message= "「". $keyword_request."」を含む名前の検索が完了しました。";
         return view('Product.searchIndex')->with([
          'products' => $products,
          'message' => $message,
          ]);
         }
     else 
        {
         $message = "キーワードを入力してください。";
           return view('Product.searchindex')->with('message',$message);
        }
    }
       
    public function store(Product $product , ProductRequest $request)
    {/*ユーザの入力データをproductテーブルにアクセスし保存する必要があるため、
       空のインスタンスを利用
    */
     $input = $request['product'];//productをキーにもつリクエストパラメータを取得
     $photo_data = $request->file('photo');
     $path = Storage::disk('s3')->putFile('foundphoto', $photo_data, 'public');// バケットの`foundphoto`フォルダへ画像をアップロード.
     $photo_data->photo = Storage::disk('s3')->url($path);//アップロードした画像のフルパスを取得
     $input += ['photo' => $photo_data->photo];//$inputにphotoとして画像のフルパスを格納
     $input += ['user_id' => $request->user()->id];//Userインスタンスのidプロパティを、$inputにuser_idとして格納。
     $product->fill($input)->save();
     
     return redirect('/');
    }
    
    public function edit(Product $product ,  Category $category)
    {
     return view('Product.edit')->with([
      'product' => $product,
      'categories' => $category->get(),
      ]);
    }
    
    public function update(Product $product , ProductRequest $request)
    {
     $input_product = $request['product'];
     $photo_data = $request->file('photo');
     $path = Storage::disk('s3')->putFile('foundphoto', $photo_data, 'public');// バケットの`foundphoto`フォルダへ画像をアップロード
     $photo_data->photo = Storage::disk('s3')->url($path);//アップロードした画像のフルパスを取得
     $input_product += ['photo' => $photo_data->photo];//$inputにphotoとして画像のフルパスを格納
     $input_product += ['user_id' => $request->user()->id];
     $product->fill($input_product)->save();
     
     return redirect('/');
    }
    
    public function delete(Product $product)
    {
     $product->delete();
     return redirect('/');
    }
}

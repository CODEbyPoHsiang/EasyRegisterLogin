<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all()->toArray();
        // return array_reverse($products);
        return response($products);
    }

    public function store(Request $request)
    {
        $rules = [
            //填入須符合的格式及長度
            'name' => 'required',
            'detail' => 'required|numeric',
     
        ];
        $messages = [
            //驗證未通過的訊息提示
            'name.required' => '品名為必填欄位',
            'detail.required' => '價格欄位不得為空',
            'detail.numeric' => '填入格式應為【數字】',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();
            $response = [
                'success' => false,
                'data' => "Error",
                'message' => $errors[0],
            ];
            return response()->json($response, 202);
        }


        $product = new Product([
            'name' => $request->input('name'),
            'detail' => $request->input('detail')
        ]);
       
        $product->save();

        return response()->json(['success'=>true,'data'=>$product,'message'=>'Product created!'], 200);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if ($product) {
            return response()->json(['success'=>true,'data'=>$product,'message'=>'Product get data ok!'], 200);
        } else {
            return response()->json(['success'=>false,'message'=>'Error get data!'], 200);
        }
    }

    public function update($id, Request $request)
    {
        $product = Product::find($id);
        if ($product) {
            $rules = [
            //填入須符合的格式及長度
            'detail' => 'required|numeric',
     
        ];
            $messages = [
            //驗證未通過的訊息提示
            'detail.required' => '價格欄位不得為空',
            'detail.numeric' => '填入格式應為【數字】',
        ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                $messages = $validator->messages();
                $errors = $messages->all();
                $response = [
                'success' => false,
                'data' => "Error",
                'message' => $errors[0],
            ];
                return response()->json($response, 202);
            }

       
            $product->update($request->all());

            return response()->json(['success'=>true,'data'=>$product,'message'=>'Product updated!'], 200);
        } else {
            return response()->json(['success'=>false,'message'=>'Error product updated !'], 200);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();

            return response()->json(['success'=>true,'message'=>'Product deleted!'], 200);
        } else {
            return response()->json(['success'=>false,'message'=>'Error product deleted!'], 200);
        }
    }

    public function search(Request $request)
    {
        $data = Product::where('name', 'LIKE', '%'.$request->keywords.'%')->cursor()->toArray();
        if (empty($data)) {
            $response = [
                'success' => false,
                'data' => "Error",
                'message' => "查無資料，請重新操作!",
            ];
            return response()->json($response, 202);
        }
        return response()->json($data);
    }
}
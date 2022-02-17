<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{

    /**
     * Function view for parent category
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function CategoryView(){

        $category  = Category::where('category_id', 0)->get();

    	return view('backend.category.category_view',compact('category'));
    }

    /**
     * Function of creating new category
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function CategoryStore(Request $request){

        $request->validate([
            'category_name_en' => 'required',
            'category_name_hin' => 'required',
            'category_icon' => 'required',
        ],[
            'category_name_en.required' => 'Input Category English Name',
            'category_name_hin.required' => 'Input Category Hindi Name',
        ]);

        Category::insert([
            'category_name_en' => $request->category_name_en,
            'category_name_hin' => $request->category_name_hin,
            'category_slug_en' => strtolower(str_replace(' ', '-',$request->category_name_en)),
            'category_slug_hin' => strtolower(str_replace(' ', '-',$request->category_name_hin)),
            'category_icon' => $request->category_icon,
        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // end method


    /**
     * Function of editing category view
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function CategoryEdit($id){
    	$category = Category::findOrFail($id);
    	return view('backend.category.category_edit',compact('category'));
    }

    /**
     * Function updating of category
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function CategoryUpdate(Request $request ,$id){

        Category::findOrFail($id)->update([
            'category_name_en' => $request->category_name_en,
            'category_name_hin' => $request->category_name_hin,
            'category_slug_en' => strtolower(str_replace(' ', '-',$request->category_name_en)),
            'category_slug_hin' => strtolower(str_replace(' ', '-',$request->category_name_hin)),
            'category_icon' => $request->category_icon,
        ]);

        $notification = array(
            'message' => 'Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);

    } // end method


    /**
     * Function for deleting category
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function CategoryDelete($id){

    	Category::findOrFail($id)->delete();

    	$notification = array(
			'message' => 'Category Deleted Successfully',
			'alert-type' => 'success'
		);

		return redirect()->back()->with($notification);

    } // end method


    /**
     * Function getting name by id
     *
     * @param int $id
     * @return mixed
     */
    public static function get_name_by_id( $id = 0 ) {
        $categories  = Category::where('id', $id)->first();
        $name = !empty( $categories ) && $categories->category_name_en ? $categories->category_name_en : "Deleted Category";
        return $name;
    }

    /**
     * Function for subcategory view page
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SubCategoryView(){

        $categories  = Category::where('category_id', 0)->get();
        $subcategory = Category::where('category_id', '>', 0)->get();
        return view('backend.category.subcategory_view',compact('subcategory','categories'));

    }

    /**
     * Function for subcategory saving
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SubCategoryStore(Request $request){

        $request->validate([
            'category_id' => 'required',
            'category_name_en' => 'required',
            'category_name_hin' => 'required',
        ],[
            'category_id.required' => 'Please select Any option',
            'category_name_en.required' => 'Input SubCategory English Name',
        ]);

        Category::insert([
            'category_id'       => $request->category_id,
            'category_name_en'  => $request->category_name_en,
            'category_name_hin' => $request->category_name_hin,
            'category_slug_en'  => strtolower(str_replace(' ', '-',$request->category_name_en)),
            'category_slug_hin' => strtolower(str_replace(' ', '-',$request->category_name_hin)),
        ]);

        $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // end method


    /**
     * Function for view for edit sub category
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SubCategoryEdit($id){
        $categories  = Category::where('category_id', 0)->orderBy('category_name_en','ASC')->get();
        $subcategory = Category::findOrFail($id);
        return view('backend.category.subcategory_edit',compact('subcategory','categories'));

    }

    /**
     * Function for updating subcategory
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SubCategoryUpdate(Request $request){

        $subcat_id = $request->id;

        Category::findOrFail($subcat_id)->update([
            'category_id'       => $request->category_id,
            'category_name_en'  => $request->category_name_en,
            'category_name_hin' => $request->category_name_hin,
            'category_slug_en'  => strtolower(str_replace(' ', '-',$request->category_name_en)),
            'category_slug_hin' => strtolower(str_replace(' ', '-',$request->category_name_hin)),
        ]);

        $notification = array(
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('subcategory.all')->with($notification);

    }  // end method


    /**
     * Function of deleting subcategory
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SubCategoryDelete($id){

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'SubCategory Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

    }


    /////////////// That for SUB->SUBCATEGORY ////////////////
    /**
     * Function of view subsubcategory
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SubSubCategoryView(){

        $categories     = Category::where('category_id', 0)->orderBy('category_name_en','ASC')->get();
        $subsubcategory = Category::where('category_id','>', 0)->where('subcategory_id','>', 0)->orderBy('category_name_en','ASC')->get();
        return view('backend.category.sub_subcategory_view',compact('subsubcategory','categories'));
    }


    /**
     * Function of getting subcategory for subsubcategory
     *
     * @param $category_id
     * @return false|string
     */
    public function GetSubCategory($category_id){

        $subcat = Category::where('category_id',$category_id)->orderBy('category_name_en','ASC')->get();
        return json_encode($subcat);
    }

    /**
     * Function of getting subsub for subsubcategory
     *
     * @param $subcategory_id
     * @return false|string
     */
    public function GetSubSubCategory($subcategory_id){

        $subsubcat = Category::where('subcategory_id',$subcategory_id)->orderBy('category_name_en','ASC')->get();
        return json_encode($subsubcat);
    }

    /**
     * Function of saving for subsubcategory
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SubSubCategoryStore(Request $request){

        $request->validate([
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'category_name_en' => 'required',
            'category_name_hin' => 'required',
        ],[
            'category_id.required' => 'Please select Any option',
            'category_name_en.required' => 'Input SubSubCategory English Name',
        ]);


        Category::insert([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'category_name_en' => $request->category_name_en,
            'category_name_hin' => $request->category_name_hin,
            'category_slug_en' => strtolower(str_replace(' ', '-',$request->category_name_en)),
            'category_slug_hin' => str_replace(' ', '-',$request->category_name_hin),
        ]);

        $notification = array(
            'message' => 'Sub-SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    } // end method


    /**
     * Function of edit view for subsubcategory
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function SubSubCategoryEdit($id){
        $subsubcategories = Category::findOrFail($id);

        $categories       = Category::where('category_id', 0)->orderBy('category_name_en','ASC')->get();
        $subcategories    = Category::where('category_id',  $subsubcategories->category_id)->orderBy('category_name_en','ASC')->get();
        return view('backend.category.sub_subcategory_edit',compact('categories','subcategories','subsubcategories'));
    }


    /**
     * Function of updating for subsubcategory
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SubSubCategoryUpdate(Request $request){

        $subsubcat_id = $request->id;

        Category::findOrFail($subsubcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'category_name_en' => $request->category_name_en,
            'category_name_hin' => $request->category_name_hin,
            'category_slug_en' => strtolower(str_replace(' ', '-',$request->category_name_en)),
            'category_slug_hin' => str_replace(' ', '-',$request->category_name_hin),
        ]);

        $notification = array(
            'message' => 'Sub-SubCategory Update Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('subsubcategory.all')->with($notification);

    } // end method


    /**
     * Function of deleting subsubcategory
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function SubSubCategoryDelete($id){

        Category::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Sub-SubCategory Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->back()->with($notification);

    }

}

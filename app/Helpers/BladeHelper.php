<?php

namespace App\Helpers;

use Exception;
use App\Helpers\HtmlHelper as HH;


/**
 * Like the Html helper this class provides services to write more concise and elegant blade templates
 * 
 * The HTML helpers support the generation of any HTML construct.
 * The Blade helper enforces project conventions, making the calls more concise
 * but limiting the diversity of generated HTML constructs.
 * 
 * An alternative to this helper is the creation of blade directives, however
 * I have difficulties with blade directives with multiple structured parameters.
 * 
 * @author frederic
 *        
 */
class  BladeHelper {

	
	/**
	 * returns an HTML select from a list of [string, id]
	 * @param array $values list of name, id pairs
	 * @param boolean $with_null
	 * @param string $selected
	 * @param array $attrs HTML attributes
	 * 
	 * <select name="vpmacid" id="vpmacid">
			<option value="F-CDYO">ASK13 - F-CDYO - (CJ)</option>
			<option value="F-CJRG">Ask21 - F-CJRG - (RG)</option>
			<option value="F-CERP">Asw20 - F-CERP - (UP)</option>
			<option value="F-CGKS">Asw20 - F-CGKS - (WE)</option>
			<option value="F-CFXR">xPégase - F-CFXR - (B114)</option>
		</select>
	 */
	static public function selector(
			$id = "",
			$values = [], 
			$selected = "",
			$attrs = []) {
				$attributes = array_merge($attrs, ["class" => "form-select", 'name' => $id, 'id' => $id]);
		return HH::selector($values, false, $selected, $attributes);
	}
	
	/**
	 * @param string $id
	 * @param array $values
	 * @param string $selected
	 * @param array $attrs
	 * @return string
	 */
	static public function selector_with_null(
			string $id = "",
			array $values = [],
			string $selected = "",
			array $attrs = []) {
				$attributes = array_merge($attrs, ["class" => "form-select", 'name' => $id, 'id' => $id]);
				return HH::selector($values, true, $selected, $attributes);
	}
	
	/**
	 * @param bool $checked
	 * @return string
	 */
	static public function checkbox (bool $checked) {
		// <input type="checkbox" {{($user->admin) ? 'checked' : ''}} onclick="return false;" />
		$res = '<input type="checkbox" ';
		if ($checked) $res .= 'checked';
		$res .= ' onclick="return false;" />';
		return $res;
	}
	
	/**
	 * @param string $email
	 * @return string
	 */
	static public function email(string $email) {
		return '<A HREF="mailto:' . $email . '">' . $email . '</A>';
	}
	
	/**
	 * @param string $route
	 * @param string $dusk
	 * @param string $label
	 * @return string
	 */
	static public function button_edit(string $route, string $dusk, string $label) {
		// <a href="{{ route('users.edit', $user->id)}}" class="btn btn-primary" dusk="edit_{{$user->name}}">{{__('general.edit')}}</a>
		return '<a href="' . $route . '" class="btn btn-primary" dusk="' . $dusk . '">' . $label . '</a>';		
	}
	
	/**
	 <form action="{{ route('users.destroy', $user->id)}}" method="post">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger" type="submit" dusk="delete_{{$user->name}}" >{{__('general.delete')}}</button>
     </form>
	 * 
	 * @param string $route
	 * @param string $dusk
	 * @param string $label
	 * @return string
	 */
	static public function button_delete(string $route, string $dusk, string $label) {
		$res = '<form action="' . $route .'"  method="post">' . "\n";
		$res .= '    <input type="hidden" name="_token" value="' . csrf_token() . '" />' . "\n";
		$res .= '    <input type="hidden" name="_method" value="DELETE">' . "\n";
		$res .= '    <button class="btn btn-danger" type="submit" dusk="' . $dusk . '"  >' . $label . "</button>\n";
		$res .= "</form>\n";
		return $res;
	}
}
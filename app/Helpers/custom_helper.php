<?php
// Function: used to jumble an array of color classes
if (!function_exists("jumble_color_array")) {
    function jumble_color_array()
    {
        $color_arr = array('primary', 'secondary', 'success', 'danger', 'warning', 'info');
        shuffle($color_arr);

        return $color_arr;
    }
}

if (!function_exists("jumble_array_javascript")) {
    function jumble_array_javascript()
    {
        return 'function jumbleArray(a,b,c,d){//array,placeholder,placeholder,placeholder
                    c=a.length;while(c)b=Math.random()*c--|0,d=a[c],a[c]=a[b],a[b]=d
                }';
    }
}

// Function: get color badges
if (!function_exists("get_color_badges")) {
    function get_color_badges($str)
    {
        $str_arr = explode(",", $str);
        $color_array = jumble_color_array();
        $no_of_colors = count($color_array);

        $str_final_str = '';
        if($str_arr != NULL)
        {
            $no_of_strs = count($str_arr);
            for($j = 0; $j < $no_of_strs; $j++){
                $color_index = $j >= $no_of_colors ? ($j % $no_of_colors) : $j;                
                $str_final_str .= '<span class="badge bg-label-'.$color_array[$color_index].'">'.$str_arr[$j].'</span> ';
            }
        }
        
        return $str_final_str;
    }
}

// Function: get color badges
if (!function_exists("show_color_badges_javascript")) {
    function show_color_badges_javascript()
    {
        return 'function show_color_badges(str)
                {
                    var strArr = str.split(\',\');
        
                    jumbleArray(colorArray);
                            
                    var $no_of_colors = colorArray.length;

                    var $str_final_str = "";
                    if (strArr.length != 0) {
                        var $no_of_strs = strArr.length;
                        
                        for (var $j = 0; $j < $no_of_strs; $j++) {
                            var $color_index = $j >= $no_of_colors ? ($j % $no_of_colors) : $j;
                            $str_final_str += \'<span class="badge bg-label-\' + colorArray[$color_index] + \'">\' + strArr[$j] + \'</span> \';
                        }
                    }

                    return $str_final_str;
                }';
    }
}
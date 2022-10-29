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

// Function: show color badges
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

// Function: toggle buttons
if (!function_exists("toggleButton_javascript")) {
    function toggleButton_javascript()
    {
        return 'function toggleButton(elem, currentClass, replaceClass, disabled = false) {
                    if ($(elem).hasClass(currentClass))
                        $(elem).removeClass(currentClass).addClass(replaceClass);
                
                    $(elem).prop(\'disabled\', disabled);
                }';
    }
}

// Function: clear form fields
if (!function_exists("clearFormFields_javascript")) {
    function clearFormFields_javascript()
    {
        return "function clearFormFields(elem) {

                    var formSerialization = $(elem).find(\"input,select,textarea\").serialize();
                    // alert(formSerialization);
                
                    $(elem).find('input,select,textarea').each(function() {
                
                        if ($(this).hasClass('dateField')) {
                            $(this).datepicker(\"setDate\", new Date());
                            return true;
                        }
                
                        switch ($(this).attr('type')) {
                            case 'password':
                            case 'text':
                            case 'textarea':
                            case 'file':
                            case 'date':
                            case 'number':
                            case 'tel':
                            case 'date':
                            case 'hidden':
                            case 'email':
                                    $(this).val('');
                                break;
                            case 'checkbox':
                                $(this).prop('checked', false);
                                break;
                            case 'radio':
                                //this.checked = false;
                                break;
                            case 'submit':
                                    break;
                            default:
                                if (!$(this).closest(\".table-responsive\").length)
                                    $(this).val(null).trigger('change');
                                break;
                        }
                    });
                }";
            }
}

// Function: clear form fields
if (!function_exists("blockLoader_javascript")) {
    function blockLoader_javascript()
    {
        return "function blockLoader(elem, duration = 500, alert = '') {
                    $(elem).block({
                        message: '<div class=\"spinner-border text-white\" role=\"status\"></div>',
                        timeout: duration,
                        css: {
                            backgroundColor: 'transparent',
                            border: '0'
                        },
                        overlayCSS: {
                            opacity: 0.5
                        },
                        onUnblock: function() {
                
                        }
                    });
                }";
            }
}
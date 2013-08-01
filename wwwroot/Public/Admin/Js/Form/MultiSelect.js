// +---------------------------------------------------------------------------+
// | FCS -- Fast,Compatible & Simple OOP PHP Framework                         |
// | FCS JS 基类库                                                             |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2005-2006 liu21st.com.  All rights reserved.                |
// | Website: http://www.fcs.org.cn/                                           |
// | Author : Liu21st 流年 <liu21st@gmail.com>                                 |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify it   |
// | under the terms of the GNU General Public License as published by the     |
// | Free Software Foundation; either version 2 of the License,  or (at your   |
// | option) any later version.                                                |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,  but      |
// | WITHOUT ANY WARRANTY; without even the implied warranty of                |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General |
// | Public License for more details.                                          |
// +---------------------------------------------------------------------------+

/**
 +------------------------------------------------------------------------------
 * 下拉列表多选类
 +------------------------------------------------------------------------------
 * @package    Form
 * @link       http://www.fcs.org.cn
 * @copyright  Copyright (c) 2005-2006 liu21st.com.  All rights reserved. 
 * @author     liu21st <liu21st@gmail.com>
 * @version    $Id$
 +------------------------------------------------------------------------------
 */
var isMsie = document.all ? true : false;


/**
* Adds buildMultipleSelects() to the onload event.
*/
function addEvent(element, event, func)
{
    if (isMsie) {
        element.attachEvent(event, func);
    
    } else if (element.addEventListener) {
        element.addEventListener(event, func, false);
    }
}

/**
* Handles the conversion of multiple selects
*/
function buildMultipleSelects()
{
	var selectObjects = document.getElementsByClassName('multiSelect');
    //var selectObjects = document.getElementsByTagName('select');
    if (selectObjects) {
	for (var i=0;i<selectObjects.length ;i++)
	{
            if (!selectObjects[i].multiple) {
                continue;
            }
            var ms = selectObjects[i];
            
            var disabled = ms.disabled ? true : false;
            var width    = ms.offsetWidth;
            var height   = ms.offsetHeight;
            
            var divElement            = document.createElement('div');
            divElement.style.overflow = 'auto';
            divElement.style.width    = width + "px";
            divElement.style.height   = height + "px";
            //divElement.style.border   = "2px inset white";
            //divElement.style.font = "10pt Arial";
            divElement.className      = 'customMultipleSelect';
            
            optionObjects = ms.getElementsByTagName('option');
            for (var j=0; j<optionObjects.length; ++j) {
                var spanElement = document.createElement('div');

                spanElement.style.paddingLeft = "20px";
                spanElement.style.cursor = "default";
                spanElement.className = 'customMultipleSelect_option';
                
                addEvent(spanElement, 'onmousedown', function () {if (isMsie && event.srcElement.tagName.toLowerCase() == 'div' && !event.srcElement.firstChild.disabled) {event.srcElement.childNodes[0].checked = !event.srcElement.childNodes[0].checked;}})
                
                var inputElement  = document.createElement('input');
                inputElement.type = "checkbox";
            
                if (optionObjects[j].selected) {
                    inputElement.checked        = true;
                    inputElement.defaultChecked = true;
                }

                if (disabled) {
                    inputElement.disabled = true;
                }

                inputElement.value            = optionObjects[j].value;
                inputElement.style.marginLeft = "-16px";
				inputElement.style.marginRight = "5px";
                inputElement.style.marginTop  = "-2px";
                inputElement.name             = ms.name;

                var textLabel = document.createTextNode(optionObjects[j].text);

                spanElement.appendChild(inputElement);
                spanElement.appendChild(textLabel);
                divElement.appendChild(spanElement);
            }

            ms.parentNode.insertBefore(divElement, ms);
            ms.parentNode.removeChild(ms);
        }
    }
}


addEvent(window, isMsie ? 'onload' : 'load', buildMultipleSelects);
/*

        <script src="multiSelect.js" type="text/javascript"></script>
        
        <style type="text/css">
        <!--
            .customMultipleSelect {
                overflow: auto;
                border: 2px inset white;
            }

            .customMultipleSelect_option {
                border-bottom: 1px solid #eeeeee;
                font: 10pt Tahoma;
                padding: 1px;
                padding-left: 20px;
                width: 100%;
            }
        // -->
        </style>
    </head>
<body>

<select multiple name="foo[]" style="width: 200px; height: 100px">
    <option value="1">Option 1</option>
    <option value="2">Option 2</option>
    <option value="3" selected>Option 3</option>
    <option value="4" selected>Option 4</option>
    <option value="5">Option 5</option>
    <option value="6">Option 6</option>
    <option value="7">Option 7</option>
</select>
*/
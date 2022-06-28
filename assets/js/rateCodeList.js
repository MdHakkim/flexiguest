/**
 *  Rate Code dropdown Taglist
 */

'use strict';

(function () {


  // Users List suggestion
  //------------------------------------------------------
  const TagifyRateCodeListRateCodeEl = document.querySelector('.TagifyRateCodeList');

  function tagRateCodeTemplate(tagData) {
    return `
     <tag title="${tagData.title || tagData.desc}"
       contenteditable='false'
       spellcheck='false'
       tabIndex="-1"
       class="${this.settings.classNames.tag} ${tagData.class ? tagData.class : ''}"
       ${this.getAttributes(tagData)}
     >
       <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
       <div>
         <span class='tagify__tag-text'>${tagData.name}</span>
       </div>
     </tag>
   `;
  }

  function suggestionRateCodeItemTemplate(tagData) {
    return `
     <div ${this.getAttributes(tagData)}
       class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
       tabindex="0"
       role="option"
     >
       <strong>${tagData.name}</strong>
       <span>${tagData.desc}</span>
     </div>
   `;
  }

  // initialize Tagify on the above input node reference
  let TagifyRateCodeList = new Tagify(TagifyRateCodeListRateCodeEl, {
    tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
    enforceWhitelist: true,
    skipInvalid: true, // do not temporarily add invalid tags
    mode: "select",
    fuzzySearch: false,
    dropdown: {
      closeOnSelect: true,
      enabled: 0,
      classname: 'users-list',
      maxItems: Infinity,
      mapValueTo: 'name',
      searchKeys: ['name', 'desc'] // very important to set by which keys to search for suggesttions when typing
    },
    templates: {
      tag: tagRateCodeTemplate,
      dropdownItem: suggestionRateCodeItemTemplate
    },
    whitelist: rateCodeList,
    callbacks: {
      //"remove": (e) => $('#RT_CL_CODE').val(""),
      //"blur": (e) => dropdown.hide()
    }
  });

  /*
     TagifyRateCodeList.on('dropdown:select', onSelectSuggestionRateCode);
 
     let addAllSuggestionsRateCodeEl;
 
     function onSelectSuggestionRateCode(e) {
         $('#RT_CL_CODE').val(e.detail.elm.getAttribute("rt_code"));
         //if (e.detail.elm == addAllSuggestionsRateCodeEl) TagifyRateCodeList.dropdown.selectAll.call(TagifyRateCodeList);
     }
     */

})();
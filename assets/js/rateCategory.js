/**
 *  Form Wizard
 */

'use strict';

(function () {


  // Users List suggestion
  //------------------------------------------------------
  const TagifyRateCatListRateCatEl = document.querySelector('.TagifyRateCatList');

  function tagRateCatTemplate(tagData) {
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

  function suggestionRateCatItemTemplate(tagData) {
    return `
    <div ${this.getAttributes(tagData)}
      class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
      tabindex="0"
      role="option"
    >
      <strong>${tagData.name}</strong>
      <span>${tagData.desc} | Rate Class: ${tagData.rt_class}</span>
    </div>
  `;
  }

  // initialize Tagify on the above input node reference
  let TagifyRateCatList = new Tagify(TagifyRateCatListRateCatEl, {
    tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
    enforceWhitelist: true,
    skipInvalid: true, // do not temporarily add invalid tags
    mode : "select",
    fuzzySearch: false,
    dropdown: {
      closeOnSelect: true,
      enabled: 0,
      classname: 'users-list',
      maxItems: Infinity,
      mapValueTo: 'name',
      searchKeys: ['name', 'desc', 'rt_class'] // very important to set by which keys to search for suggesttions when typing
    },
    templates: {
      tag: tagRateCatTemplate,
      dropdownItem: suggestionRateCatItemTemplate
    },
    whitelist: rateCategoryList,
    callbacks: {
      "remove": (e) => $('#RT_CL_CODE').val(""),
      "blur": (e) => dropdown.hide()	
    }
  });

  TagifyRateCatList.on('dropdown:select', onSelectSuggestionRateCat);

  let addAllSuggestionsRateCatEl;

  function onSelectSuggestionRateCat(e) {
    $('#RT_CL_CODE').val(e.detail.elm.getAttribute("rt_class"));
    //if (e.detail.elm == addAllSuggestionsRateCatEl) TagifyRateCatList.dropdown.selectAll.call(TagifyRateCatList);
  }
  
})();
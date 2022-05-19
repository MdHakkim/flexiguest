/**
 *  Form Wizard
 */

'use strict';

(function () {

  const select2 = $('.select2'),
    dateField = $('.dateField'),
    tagifyElems = $('.TagifyRoomTypeList,.TagifyRateCatList');
  //const selectPicker = $('.selectpicker');

  // Users List suggestion
  //------------------------------------------------------
  var TagifyPackageCodeListPackageCodeEl = document.querySelectorAll('.TagifyPackageCodeList');

  function tagPackageCodeTemplate(tagData) {
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

  function suggestionPackageCodeItemTemplate(tagData) {
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

  TagifyPackageCodeListPackageCodeEl.forEach(function (el) {

    // initialize Tagify on the above input node reference
    let TagifyPackageCodeList = new Tagify(el, {
      tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
      enforceWhitelist: true,
      skipInvalid: true, // do not temporarily add invalid tags
      fuzzySearch: false,
      dropdown: {
        closeOnSelect: false,
        enabled: 0,
        classname: 'users-list',
        maxItems: 1000,
        mapValueTo: 'name',
        searchKeys: ['name', 'desc'] // very important to set by which keys to search for suggesttions when typing
      },
      templates: {
        tag: tagPackageCodeTemplate,
        dropdownItem: suggestionPackageCodeItemTemplate
      },
      whitelist: packageCodeList,
      callbacks: {
        //"remove": (e) => $('#RT_CL_CODE').val(""),
        "blur": (e) => dropdown.hide()
      }
    });

    TagifyPackageCodeList.on('dropdown:show dropdown:updated', onDropdownPackageCodeShow);
    TagifyPackageCodeList.on('dropdown:select', onSelectPackageCodeSuggestion);

    let addAllPackageCodeSuggestionsEl;

    function onDropdownPackageCodeShow(e) {
      let dropdownContentEl = e.detail.tagify.DOM.dropdown.content;

      if (TagifyPackageCodeList.suggestedListItems.length > 1) {
        addAllPackageCodeSuggestionsEl = getAddAllSuggestionsEl();

        // insert "addAllPackageCodeSuggestionsEl" as the first element in the suggestions list
        dropdownContentEl.insertBefore(addAllPackageCodeSuggestionsEl, dropdownContentEl.firstChild);
      }
    }

    function onSelectPackageCodeSuggestion(e) {
      if (e.detail.elm == addAllPackageCodeSuggestionsEl) TagifyPackageCodeList.dropdown.selectAll.call(TagifyPackageCodeList);
    }

    // create an "add all" custom suggestion element every time the dropdown changes
    function getAddAllSuggestionsEl() {
      // suggestions items should be based on "dropdownItem" template
      return TagifyPackageCodeList.parseTemplate('dropdownItem', [{
        class: 'addAll',
        name: 'Add all',
        desc: TagifyPackageCodeList.settings.whitelist.reduce(function (remainingSuggestions, item) {
          return TagifyPackageCodeList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1;
        }, 0) + ' Packages'
      }]);
    }

    if (el.id == 'RT_CD_PACKAGES' && typeof selectedPackageCodes !== 'undefined' && selectedPackageCodes != '') {
      TagifyPackageCodeList.addTags(selectedPackageCodes);
    }

  });


})();
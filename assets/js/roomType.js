/**
 *  Form Wizard
 */

'use strict';

(function () {


  // Users List suggestion
  //------------------------------------------------------
  var TagifyRoomTypeListRoomTypeEl = document.querySelectorAll('.TagifyRoomTypeList');

  function tagRoomTypeTemplate(tagData) {
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

  function suggestionRoomTypeItemTemplate(tagData) {
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

  TagifyRoomTypeListRoomTypeEl.forEach(function (el) {

    // initialize Tagify on the above input node reference
    let TagifyRoomTypeList = new Tagify(el, {
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
        tag: tagRoomTypeTemplate,
        dropdownItem: suggestionRoomTypeItemTemplate
      },
      whitelist: roomTypeList,
      callbacks: {
        //"remove": (e) => $('#RT_CL_CODE').val(""),
        "blur": (e) => dropdown.hide()
      }
    });

    TagifyRoomTypeList.on('dropdown:show dropdown:updated', onDropdownRoomTypeShow);
    TagifyRoomTypeList.on('dropdown:select', onSelectRoomTypeSuggestion);

    let addAllRoomTypeSuggestionsEl;

    function onDropdownRoomTypeShow(e) {
      let dropdownContentEl = e.detail.tagify.DOM.dropdown.content;

      if (TagifyRoomTypeList.suggestedListItems.length > 1) {
        addAllRoomTypeSuggestionsEl = getAddAllSuggestionsEl();

        // insert "addAllRoomTypeSuggestionsEl" as the first element in the suggestions list
        dropdownContentEl.insertBefore(addAllRoomTypeSuggestionsEl, dropdownContentEl.firstChild);
      }
    }

    function onSelectRoomTypeSuggestion(e) {
      if (e.detail.elm == addAllRoomTypeSuggestionsEl) TagifyRoomTypeList.dropdown.selectAll.call(TagifyRoomTypeList);
    }

    // create an "add all" custom suggestion element every time the dropdown changes
    function getAddAllSuggestionsEl() {
      // suggestions items should be based on "dropdownItem" template
      return TagifyRoomTypeList.parseTemplate('dropdownItem', [{
        class: 'addAll',
        name: 'Add all',
        desc: TagifyRoomTypeList.settings.whitelist.reduce(function (remainingSuggestions, item) {
          return TagifyRoomTypeList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1;
        }, 0) + ' Room Types'
      }]);
    }

    if (el.id == 'RT_CD_ROOM_TYPES' && typeof selectedRoomTypes !== 'undefined' && selectedRoomTypes != '') {
      TagifyRoomTypeList.addTags(selectedRoomTypes);
    }

  });


})();
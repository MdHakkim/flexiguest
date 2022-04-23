/**
 *  Form Wizard
 */

'use strict';

(function () {

  
  // Users List suggestion
  //------------------------------------------------------
  const TagifyPkgGroupListEl = document.querySelector('.TagifyPkgGroupList');

  const pkgGroupsList = [
    {
        "value": "1",
        "name": "FOOD",
        "email": "Breakfast"
    },
    {
        "value": "2",
        "name": "FRUIT BASKET",
        "email": "Fresh Fruit in Season"
    },
    {
        "value": "3",
        "name": "CHAMP",
        "email": "Dom Perignon and 12 Red Roses"
    },
    {
        "value": "4",
        "name": "ROBE",
        "email": "Bath Robe"
    }
];

  function tagTemplate(tagData) {
    return `
    <tag title="${tagData.name || tagData.email}"
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

  function suggestionItemTemplate(tagData) {
    return `
    <div ${this.getAttributes(tagData)}
      class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
      tabindex="0"
      role="option"
    >
      ${
        tagData.avatar
          ? `<div class='tagify__dropdown__item__avatar-wrap'>
          <img onerror="this.style.visibility='hidden'" src="${tagData.avatar}">
        </div>`
          : ''
      }
      <strong>${tagData.name}</strong>
      <span>${tagData.email}</span>
    </div>
  `;
  }

  // initialize Tagify on the above input node reference
  let TagifyPkgGroupList = new Tagify(TagifyPkgGroupListEl, {
    tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
    enforceWhitelist: true,
    skipInvalid: true, // do not remporarily add invalid tags
    fuzzySearch: false,
    dropdown: {
      closeOnSelect: false,
      enabled: 0,
      classname: 'users-list',
      maxItems: -1,
      mapValueTo: 'name',
      searchKeys: ['name', 'email'] // very important to set by which keys to search for suggesttions when typing
    },
    templates: {
      tag: tagTemplate,
      dropdownItem: suggestionItemTemplate
    },
    whitelist: pkgGroupsList
  });

  TagifyPkgGroupList.on('dropdown:show dropdown:updated', onDropdownShow);
  TagifyPkgGroupList.on('dropdown:select', onSelectSuggestion);

  let addAllSuggestionsEl;

  function onDropdownShow(e) {
    let dropdownContentEl = e.detail.tagify.DOM.dropdown.content;

    if (TagifyPkgGroupList.suggestedListItems.length > 1) {
      addAllSuggestionsEl = getAddAllSuggestionsEl();

      // insert "addAllSuggestionsEl" as the first element in the suggestions list
      dropdownContentEl.insertBefore(addAllSuggestionsEl, dropdownContentEl.firstChild);
    }
  }

  function onSelectSuggestion(e) {
    if (e.detail.elm == addAllSuggestionsEl) TagifyPkgGroupList.dropdown.selectAll.call(TagifyPkgGroupList);
  }

  // create an "add all" custom suggestion element every time the dropdown changes
  function getAddAllSuggestionsEl() {
    // suggestions items should be based on "dropdownItem" template
    return TagifyPkgGroupList.parseTemplate('dropdownItem', [
      {
        class: 'addAll',
        name: 'Add all',
        email:
          TagifyPkgGroupList.settings.whitelist.reduce(function (remainingSuggestions, item) {
            return TagifyPkgGroupList.isTagDuplicate(item.value) ? remainingSuggestions : remainingSuggestions + 1;
          }, 0) + ' Packages'
      }
    ]);
  }

})();

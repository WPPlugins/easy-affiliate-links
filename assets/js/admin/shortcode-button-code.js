var eafl_code_editor = eafl_code_editor || {};

eafl_code_editor.button = function(button, textarea) {
    var selection = eafl_code_editor.get_selection().text;

    if(typeof tinymce != 'undefined') {
        if(typeof tinymce.activeEditor != 'undefined' && tinymce.activeEditor != null) {
            eafl_admin.open_modal(tinymce.activeEditor.id, { selection: selection });
        } else {
            eafl_admin.open_modal(textarea.id, { selection: selection });
        }
    } else {
        eafl_admin.open_modal(textarea.id, { selection: selection });
    }
}

eafl_code_editor.get_selection = function() {
    var textComponent;
    textComponent = document.getElementById('replycontent');
    if (typeof textComponent == 'undefined' || !jQuery(textComponent).is(':visible')) // Not a comment reply
        textComponent = document.getElementById("content");

    var selectedText = {};

    if (parent.document.selection != undefined) { // IE
        textComponent.focus();
        var sel = parent.document.selection.createRange();
        selectedText.text = sel.text;
        selectedText.start = sel.start;
        selectedText.end = sel.end;
    } else if (textComponent.selectionStart != undefined) { // Mozilla
        var startPos = textComponent.selectionStart;
        var endPos = textComponent.selectionEnd;
        selectedText.text = textComponent.value.substring(startPos, endPos)
        selectedText.start = startPos;
        selectedText.end = endPos;
    }

    return selectedText;
};

jQuery(document).ready(function($) {
    if (typeof QTags != 'undefined') {
        QTags.addButton('Easy_Affiliate_Link', 'easy affiliate link', eafl_code_editor.button, '', '', 'Easy Affiliate Link', 30);
    }
});
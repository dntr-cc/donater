import 'tinymce/tinymce';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import 'tinymce/skins/content/default/content.css';
import 'tinymce/icons/default/icons';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';
import 'tinymce/plugins/link';
import 'tinymce/plugins/table';
import 'tinymce/plugins/media';

window.baseTinymceConfig = {
    selector: 'textarea',
    plugins: ["table", "link", "media"],
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
    skin: false,
    content_css: false,
    menu: {
        edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
        insert: { title: 'Insert', items: 'link pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime' },
        format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat' },
    },
};
window.addEventListener('DOMContentLoaded', () => {
    tinymce.init(window.baseTinymceConfig);
});

window.tinymce = tinymce;

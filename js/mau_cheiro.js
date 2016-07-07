var editor = ace.edit("editor");
editor.setTheme("ace/theme/github");
editor.session.setMode("ace/mode/pgsql");
editor.getSession().setUseWrapMode(true);

$(document).ready(function(){
    $(".btn-success").click(function(){
         $("#sql").val(editor.getValue());
        if (confirm("A refatoração será executada!") == true) $("#form_refetorar").submit();
    });
});
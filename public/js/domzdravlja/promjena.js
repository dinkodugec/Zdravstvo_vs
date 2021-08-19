$('#uvjet').autocomplete({
    source: function(req,res){
        $.ajax({
            url:'/pacijent/traziPacijenta',
            data:{
                uvjet: req.term,
                domzdravlja: domzdravlja
            },
            success: function(odgovor){
                res(odgovor);
            }
        });
    },
    minLength: 2,
    select: function(dogadaj,stavka){
        console.log(stavka);
    }
}).autocomplete('instance')._renderItem=function(ul,item){
    return $('<li>').append(item.ime).appendTo(ul);
};
$('#uvjet').autocomplete({
    source: function(req,res){
        $.ajax({
            url:'/Pacijent/traziPacijenta',
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

function spremi(domzdravlja,pacijent){
    
    $.ajax({
        type:'POST',
        url:'/domzdravlja/dodajPacijenta',
        data:{
            pacijent: pacijent.sifra,
            domzdravlja: domzdravlja
        },
        success: function(odgovor){
            if(odgovor==='OK'){
                $('#pacijenti').append('<tr>' +
                '<td>' + pacijent.ime + ' ' + pacijent.prezime + '</td>' +
                '<td>' +
                '<a class="brisanje" href="#" id="p_' + pacijent.sifra + '">' +
                    '<i title="brisanje" style="color: red" class="fas fa-trash-alt" aria-hidden="true"></i><span class="sr-only">brisanje</span>' +
                '</a>' +
                '</td>' +
            '</tr>');
            definirajBrisanje();
            }else{
                alert(odgovor);
            }
        }
    });
}

function definirajBrisanje(){
    $('.brisanje').click(function(){
        let element=$(this);
        let sifra = element.attr('id').split('_')[1];
        
        $.ajax({
            type:'POST',
            url:'/domzdravlja/obrisiPacijenta',
            data:{
                pacijent: sifra,
                domzdravlja: grupa
            },
            success: function(odgovor){
                if(odgovor==='OK'){
                    element.parent().parent().remove();
                }else{
                    alert(odgovor);
                }
               
            }
        });
        return false;
    });
}
definirajBrisanje();
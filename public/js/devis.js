document.querySelectorAll('a').forEach(function (link) {
    link.addEventListener('click', onClickBtn);
});


function onClickBtn(event) {
    console.log("j'ai cliqué !!!!");
    event.preventDefault();

    $(this).addClass('selected');
    $('a.link-details').not('.selected').remove();
    const row2 = $('#quantite');
    row2.toggle();
        $("#slider").slider({
            value: "",
            step: 30,
            min: 30,
            max: 600,
            slide: function (event, ui) {
                $('#nbre').val(ui.value);
            }
        });
    const sliderValue = $("#nbre").val($("#slider").slider("value"));
    const url = this.href;

    axios.get(url).then(function (response) {
        const produit = response.data.produit;
        console.log(produit);
        const qte = sliderValue.value();
        console.log(qte);
    })
}
(function ($) {
  "use strict";

  /* ==============  priceing table  ============== */
  $('.iteck-pricing-plans #monthly-input2').on('change', function () {
    $(".iteck-pricing-plans .yearly_price , .iteck-pricing-plans .monthly_price").toggleClass("show");
  });

  /* ==============  priceing table  ============== */
  $('.iteck-pricing-plans.slider-pricing #yearly-input2').on('change', function () {
    $(".iteck-pricing-plans.slider-pricing .yearly_price, .iteck-pricing-plans.slider-pricing .monthly_price").toggleClass("show");
  });

  /* ==============  priceing plans  ============== */
  $('.iteck-price-table #monthly-input').on('change', function () {
    $(".iteck-price-table .monthly_price").show();
    $(".iteck-price-table .monthly_price").siblings(".yearly_price").hide();
  });

  $('.iteck-price-table #yearly-input').on('change', function () {
    $(".iteck-price-table .yearly_price").show();
    $(".iteck-price-table .yearly_price").siblings(".monthly_price").hide();
  });

  $(function () {
    $("#slider-range-min").slider({
      range: "min",
      value: 25,
      min: 1,
      max: 100,
      slide: function (event, ui) {
        $("#amount").val(ui.value);
      }
    });
    $("#amount").val($("#slider-range-min").slider("value"));
  });

})(jQuery);
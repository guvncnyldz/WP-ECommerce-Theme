<?php

function get_country_list($country, $state, $city)
{
    $countries_obj = new WC_Countries();
    $countries = $countries_obj->__get('countries');
    $is_country_choosed = false;
    echo '<div class="input-group">';
    echo '<label for="">Ülke Seçiniz <span>*</span></label>';
    echo '<select required name="country" id="country">';
    echo '<option value disabled selected>Ülke Seçiniz</option>';
    foreach (array_keys($countries) as $code) {
        if ($country == $code) {
            $is_country_choosed = true;
            $choosed_country = $code;
            echo '<option selected value="' . $code . '">' . $countries[$code] . '</option>';
        } else
            echo '<option value="' . $code . '">' . $countries[$code] . '</option>';
    }
    echo '</select></div>';

    $states = $countries_obj->get_states($choosed_country);


    echo '<div class="input-group" id="state_input">';
    echo '<label for="">Şehir Seçiniz <span>*</span></label>';
    if ($states) {

        echo '<select required name="state" id="state">';
        echo '<option disabled selected value>Şehir Seçiniz </option>';
        if ($is_country_choosed)
            foreach (array_keys($states) as $code) {
                if ($state == $code) {
                    echo '<option selected value="' . $code . '">' . $states[$code] . '</option>';
                } else
                    echo '<option value="' . $code . '">' . $states[$code] . '</option>';
            }
    } else {
        echo '<input id="state_text" required name="state"
                                               value="' . $state . '" type="text">';
    }
    echo '</select></div>';

    echo '<script>
                                        $("select#country").change(function () {
                                            var selectedCountry = $(this).children("option:selected").val();

                                            $.post(my_ajax_object.ajax_url, {
                                                action: "get_cities",
                                                country_code: selectedCountry
                                            }, function (response) {
                                                // Log the response to the console
                                                cities = JSON.parse(response)
                                                if (cities) {
                                                    if ($("#state").length == 0) {
                                                    console.log($("#state"))
                                                        $("#state_text").remove();
                                                        $("#state_input").append(`<select name="state" id="state">
                                                                                    <option disabled selected value>Ülke Seçmelisiniz</option>
                                                                                </select>`)
                                                    }
                                                    $("#state")
                                                        .find("option")
                                                        .remove()
                                                        .end()
                                                        .append("<option disabled selected value>Şehir Seçiniz </option>")

                                                    for (state_code in cities) {
                                                        $("#state").append("<option value=" + state_code + ">" + cities[state_code] + "</option>")
                                                    }
                                                } else {
                                                    if ($("#state_text").length == 0) {
                                                        $("#state").remove();
                                                        $("#state_input").append(`<input id="state_text" required name="state"
                                               value="" type="text">`)
                                                    }
                                                    $("#state")
                                                        .find("option")
                                                        .remove()
                                                        .end()
                                                        .append("<option disabled selected value>Şehir Seçiniz </option>")
                                                }

                                            });
                                        });
                                    </script>
                                    
                                    <div class="input-group">
                                        <label for="">İlçe <span>*</span></label>
                                        <input required name="city"
                                               value="' . $city . '" type="text">
                                    </div>';
}


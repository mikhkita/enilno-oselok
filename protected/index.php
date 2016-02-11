<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("title", "Займы для юридических лиц и ИП");

$APPLICATION->SetTitle("Займы для юридических лиц и ИП");

$APPLICATION->AddHeadString('<link href="'.SITE_TEMPLATE_PATH.'/styles/foundation/foundation.css";  type="text/css" rel="stylesheet" />',true);

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/javascripts/calculator.js");

$sum_zajm = isset($_REQUEST["sum_zajm"]) ? $_REQUEST["sum_zajm"] : "450000";
$srok_zajm = isset($_REQUEST["srok_zajm"]) ? $_REQUEST["srok_zajm"] : "4";
?>

<div id="bl_about_us">

	<section id="bl_modal" class="fade modal">

		<div class="modal-dialog modal-lg">

			<div class="modal-content">

				<div class="modal-header">

					<button type="button" data-dismiss="modal" aria-hidden="true" class="close">

						<svg xml:space="preserve" viewbox="0 0 512 512" height="45px" width="30px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" id="Layer_1"
                        <polygon points="476.153,94.43 417.569,35.847 256,197.417 94.431,35.847 35.847,94.43 197.416,256 35.847,417.57 94.431,476.153 256,314.583 417.569,476.153 476.153,417.57 314.584,256 " id="my_cross">

                        </polygon>
                        </svg>
					</button>

				</div>

				<div aria-hidden="true" aria-labelledby="myLargeModalLabel" role="dialog" tabindex="-1" class="modal-body">

					<h1>Микрозаймы для юридических лиц и индивидуальных предпринимателей в Москве</h1>

					<p>Микрозаймы отличное решение, если вам нужно получить небольшую сумму денег на короткий срок. Всевозможные виды микрофинансирования становятся все более популярными как в столице, так и за пределами Москвы и Подмосковья.</p>

					<p>Как часто от осуществления заветной мечты нас отделяет всего пара шагов? Но зачем откладывать финансирование своего малого бизнеса из-за отсутствия нескольких десятков тысяч?</p>

					<h3>Секрет востребованности в экономии времени и доступности</h3>

					<p>Получение микрозайма дает юридическим лицам гарантированную поддержку, позволяет осуществлять краткосрочные планы и при этом оставаться конкурентоспособным на рынке товаров и услуг.</p>

					<p>Займы для индивидуальных предпринимателей, займы для малого бизнеса, займы для юридических лиц. Наша компания способна профинансировать ваш бизнес в сжатые сроки и без лишних документаций.</p>

					<h3>Сотрудничая с нами вы получаете</h3>

					<ul>

						<li>Займы для юридических лиц, в том числе и целевые микрозаймы в размере от 300000 до миллиона рублей - это позволит поставить бизнес на ноги, исходя из его индивидуальных параметров и потребностей </li>

						<li>Сокращенное время рассмотрения заявки на микрозаймы - в течение часа вы получите информацию о решении предоставления займа. </li>

						<li>Удобные формы получения рефинансирования - по желанию клиента средства перечисляются на счет фирмы или лицевой счет индивидуального предпринимателя. </li>

						<li>Умеренную процентную ставку. </li>

						<li>Возможность погасить займ в срок до 36 месяцев. Это дополнительное время позволит скорректировать производительность бизнеса. </li>

					</ul>

				</div>

			</div>

		</div>

	</section>

</div>


<div id="bl_modal_zaem" class="modal fade">

    <div class="modal-dialog" style="width: 900px;">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i></i>Закрыть</button>
                <!--h4 class="modal-title"></h4-->
            </div>

            <div class="modal-body b-garanty" style="padding-left:30px;">

                <div id="online-request" class="b-modal-zaem">
                    <? $APPLICATION->IncludeComponent(
                        "itlogic:loan.first.add.ra",
                        ".default",
                        array(
                            "PERSONS" => array(
                                0 => "1",
                                1 => "2",
                            ),
                            "GROUPS_PERSON_1" => array(
                                0 => "5",
                                1 => "6",
                                2 => "7",
                                3 => "8",
                                4 => "247",
                            ),
                            "PROPERTIES_PERSON_1" => array(
                                0 => "34",
                                1 => "35",
                                2 => "36",
                                3 => "37",
                                4 => "38",
                                5 => "39",
                                6 => "40",
                                7 => "41",
                                8 => "42",
                                9 => "43",
                                10 => "44",
                                11 => "45",
                                12 => "185",
                                13 => "1269",
                                14 => "1271",
                            ),
                            "GROUPS_PERSON_2" => array(
                                0 => "9",
                                1 => "10",
                                2 => "11",
                                3 => "12",
                            ),
                            "PROPERTIES_PERSON_2" => array(
                                0 => "46",
                                1 => "47",
                                2 => "48",
                                3 => "49",
                                4 => "50",
                                5 => "51",
                                6 => "52",
                                7 => "53",
                                8 => "54",
                            ),
                            "VALIDATE_DATE" => array(
                                /*0 => "38",
                                1 => "41",*/
                            ),
                            "VALIDATE_PHONE" => array(
                                0 => "185",
                                1 => "52",
                            ),
                            "VALIDATE_EMAIL" => array(
                                /*0 => "44",
                                1 => "53",*/
                            ),
                            "CHOOSE_PROPS_1" => array(
                                0 => "42",
                                1 => "43",
                            ),
                            "CHOOSE_PROPS_2" => array(
                                0 => "47",
                                1 => "48",
                            ),
                            "VALIDATE_INN" => array(
                                0 => "42",
                            ),
                            "VALIDATE_INN_LAW" => array(
                                0 => "47",
                            ),
                            "VALIDATE_OGRN" => array(
                                0 => "48",
                            ),
                            "VALIDATE_OGRNIP" => array(
                                0 => "43",
                            ),
                            "VALIDATE_DISTRICT" => array(
                                0 => "40",
                            ),
                            "VALIDATE_PASSPORT" => array(
                                0 => "37",
                            ),
                            "VALIDATE_INDEX" => array(
                            ),
                            "SUM_ZAJM" => $sum_zajm,
                            "SROK_ZAJM" => $srok_zajm,
                            "PROPERTY_38" => "01.01.2016",
                            "PROPERTY_41" => "01.01.2016"
                        ),
                        false
                    ); ?>
                </div>

            </div>

            <div class="modal-footer">
                <!--button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button-->
            </div>

        </div>

    </div>

</div>

<div id="bl_about_us">
	<section id="bl_modal_2" class="fade modal">

		<div class="modal-dialog modal-lg">

			<div class="modal-content">

				<div class="modal-header">

					<button type="button" data-dismiss="modal" aria-hidden="true" class="close"> <svg xml:space="preserve" viewbox="0 0 512 512" height="45px" width="30px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" id="Layer_1"> <polygon points="476.153,94.43 417.569,35.847 256,197.417 94.431,35.847 35.847,94.43 197.416,256 35.847,417.57 94.431,476.153 256,314.583 417.569,476.153 476.153,417.57 314.584,256 " id="my_cross"></polygon> </svg>
					</button>

				</div>

				<div aria-hidden="true" aria-labelledby="myLargeModalLabel" role="dialog" tabindex="-1" class="modal-body">

					<h1>Условия</h1>

					<div class="table-responsive">

						<table class="table">

							<tbody>

							  <tr> <td class="">Клиент – далее по тексту Заемщик</td> <td class="">Юридическое лицо, Индивидуальный предприниматель – резиденты РФ </td> </tr>

							  <tr> <td class="">Назначение займа</td> <td class="">целевой</td> </tr>

							  <tr> <td class="">Обеспечение</td> <td class="">2 поручителя</td> </tr>

							  <tr> <td class="">Форма займа</td> <td class="">Безналичный перевод на р/с ЮЛ, ИП</td> </tr>

							  <tr> <td class="">Срок займа</td> <td class="">от 3 до 18 месяцев</td> </tr>

							  <tr> <td class="">Порядок уплаты процентов на займ</td> <td class="">2 раза в месяц</td> </tr>

							  <tr> <td class="">Отсрочка основного тела займа</td> <td class="">Предусмотрено, с отсрочкой 1/2/3 месяца</td> </tr>

							  <tr> <td class="">Возможность досрочного погашения займа</td> <td class="">Заемщик уведомляет в письменной форме не позднее, чем за 5 рабочих дней до планируемой даты погашения. Частичное и полное досрочное погашение возможно только в дату платежа, установленную Графиком погашения задолженности. При частично досрочном погашении составляется Новый график платежей</td> </tr>

							  <tr> <td class="">Пени за допуск просроченной задолженности</td> <td class="">0,2 % от суммы пропущенного платежа в день</td> </tr>

							  <tr> <td class="">Ставка займа</td> <td class="">0,16 % в 1 календарный день</td> </tr>

							  <tr> <td class="">Валюта займа</td> <td class="">Рубли</td> </tr>

							  <tr> <td class="">Минимальная сумма займа</td> <td class="">300 000 рублей</td> </tr>

							  <tr> <td class="">Максимальная сумма займа</td> <td class="">1 000 000 рублей</td> </tr>

							 </tbody>

						</table>

						<span></span>

						<p>При повторном обращении Клиента, займ рассматривается на индивидуальных условиях.</p>

					</div>

					<h3>Преимущества</h3>

					<ul>
					<li>Аннуитентное погашение займа, дает возможность, прогнозировать Ваш бизнес, так как все платежи разделены равными долями;</li>

					<li>Возможность снизить налогооблагаемую базу, путем включения расходов на займ;</li>

					<li>Возможность улучшить кредитную историю компании;</li>

					<li>Вы получите комплексную поддержку Вашего бизнеса.</li>
					</ul>

					<h3>Выгода</h3>

					<ul>
					<li>Мы предоставляем индивидуальные условия каждому заемщику;</li>

					<li>Мы предлагаем заемные средства без залога на различные сроки - от 3-х месяцев до 1 года;</li>

					<li>Заявка на займ рассматривается от одного до трех дней, после чего денежные средства сразу же перечисляются на Ваш счет, открытый в любом банке;</li>

					<li>Для оформления заявки Вам всего лишь необходимо заполнить Анкету–Заявку.</li>
					</ul>

					<h3>Основные требования к заемщику</h3>

					<ul>
					<li>Срок ведения бизнеса не менее 3 месяцев;</li>

					<li>Регистрация бизнеса в регионе местонахождения МФО;</li>

					<li>Отсутствие задолженности по налогам и сборам.</li>
					</ul>

					<h3>Для ЮЛ</h3>

					<ol>
					<li>Регистрационные и учредительные документы:</li>

					<ul>
					<li style="list-style: none; height: 0">&nbsp;</li>
					<li style="list-style: none;">Документы физического лица:</li>

					<li>Паспорт гражданина РФ;</li>

					<li>ИНН;</li>

					<li>Водительское удостоверение или заграничный паспорт;</li>

					<li style="list-style: none; height: 0">&nbsp;</li>

					<li style="list-style: none;">Документы юридического лица:</li>

					<li>Свидетельство о государственной регистрации юридического лица;</li>

					<li>Свидетельство о постановке на учет в налоговом органе;</li>

					<li>Паспорта учредителей юридического лица.</li>
					</ul>

					<li>Документы по хозяйственной деятельности:</li>

					<ul>
					<li>Разрешение на занятие отдельными видами деятельности (лицензия), свидетельство о допуске строительным работам (при наличии).</li>
					</ul>

					<li>Финансовые документы:</li>

					<ul>
					<li>Управленческую отчётность за последний год;</li>

					<li>Копии договоров (контрактов) с контрагентами по бизнесу (при их наличии);</li>

					<li>Расширенная выписка из банка с расчетного счета (за 6 мес.).</li>
					</ul>

					<li>Документы для поручителя:</li>

					<ul>
					<li>Паспорт гражданина РФ;</li>

					<li>ИНН;</li>

					<li>Водительское удостоверение или заграничный паспорт.</li>
					</ul>

					<p>*Документы предоставляются в оригинале, сотрудник ООО «Наше время» копирует и заверяет.</p>
					</ol>

					<h3>Для ИП</h3>

					<ol>
					<li>Документы физического лица:</li>

					<ul>
					<li>Паспорт гражданина РФ;</li>

					<li>ИНН;</li>

					<li>Водительское удостоверение или заграничный паспорт.</li>
					</ul>

					<li>Документы предпринимателя:</li>

					<ul>
					<li>Свидетельство о регистрации в Едином государственном реестре индивидуальных предпринимателей (ЕГРИП);</li>

					<li>Свидетельство, выданное Федеральной налоговой службой Российской Федерации, о постановке на учет в налоговом органе (ИНН).</li>
					</ul>

					<li>Документы по хозяйственной деятельности:</li>

					<ul>
					<li>Разрешение на занятие отдельными видами деятельности (лицензия), свидетельство о допуске к строительным работам (при наличии).</li>
					</ul>

					<li>Финансовые документы:</li>

					<ul>
					<li>Управленческая отчетность;</li>

					<li>Расширенная выписка из банка с расчетного счета (за 6 мес.);</li>

					<li>Копии договоров (контрактов) с контрагентами по бизнесу (при их наличии).</li>
					</ul>

					<li>Документы для поручителя:</li>

					<ul>
					<li>Паспорт гражданина РФ;</li>

					<li>ИНН;</li>

					<li>Водительское удостоверение или заграничный паспорт.</li>
					</ul>

					<p>*Документы предоставляются в оригинале, сотрудник ООО «Наше время» копирует и заверяет.</p>
					</ol>

					<h3>Порядок предоставления и расчёта займа</h3>

					<ul>
					<li>Выдача займа производится единовременно путем безналичного перечисления денежных средств на расчетный счет Заемщика;</li>

					<li>Уплата процентов на займ осуществляется каждые пятнадцать дней пользование займа;</li>

					<li>Очередность погашения при просрочке уплачивается: неустойка; проценты; сумма займа.</li>
					</ul>

					<h3>Условия досрочного расторжения займа</h3>

					<ul>
					<li>При досрочном частичном и или полном досрочном погашении займа Заемщик обязан в письменной форме не позднее, чем за 5 рабочих дней до планируемой даты погашения. Частичное и полное досрочное погашение возможно только в дату платежа, установленную Графиком погашения задолженности. При частично досрочном погашении составляется Новый график платежей.</li>
					</ul>

				</div>

			</div>

		</div>

	</section>
</div>


<div class="block relative">
<div id="t_big-picture-investors" class="t_big-picture" style="background: url(<?=SITE_TEMPLATE_PATH?>/images/micro-main-photo.png) no-repeat 50% 0; height: 512px;">
    <div class="t_fixed-body-width">
        <div class="t_big-text col-xs-12 col-sm-12">
            <div class="title text-center top-offset-20per">

                <h1 class="text-color-1">

                    Микрофинансирование

                </h1>

                <p class="text-color-3">

                    Теперь все просто — мы легко выдаем финансовую поддержку индивидуальным предпринимателям, малому и среднему бизнесу
                </p>

            </div>
        </div>
    </div>
</div>


<div id="bl_calculate_benefits" class="container-fluid">

	<div class="container t_fixed-body-width">
	<div class="tab-content-cont" style="    margin-top: -215px; margin-bottom: 60px;">
        <div class="b-left-line"></div>
        <div class="b-right-line"></div>
        <div class="b-bottom-line"></div>
    <div class="bl_calculator text-color-3" style="margin-top: 0px;">
	<h2 class="uppercase">Рассчитать выгоду</h2>
      <div class="fond_slider">
        <div class="row_calculator">
			<div>
				<br/>
				<span>
					<img src="<?=SITE_TEMPLATE_PATH?>/images/icon-calculator-money-color-2.png"  />
				</span>
				<label class="text-color-5" style="margin-top: 12px;">Сумма</label>
			</div>
            <div class="divisions-found" style="margin-bottom: 5px;">
              <div class="divide divide_50"> <span class="divide_value">300 тыс.</span> </div>

              <div class="divide divide_50"> <span class="divide_value">650 тыс.</span> </div>

              <div class="divide divide_50"> <span class="divide_value">1 млн.</span> </div>
            </div>
          <div class="foundationSlider">
            <div class="range-slider vertical-range round" id="slider_zaim_sum" data-slider="450000" data-options="display_selector: #sliderOutput_zaim_sum; initial: 0; start: 300000; end: 1000000; step: 1000;">
				<span class="range-slider-handle border-color-2" role="slider_zaim_sum" tabindex="0" aria-labelledby="sliderLabel"></span>
				<span class="range-slider-active-segment background-color-5"></span>
				<input type="hidden" />
			</div>
           </div>

          <div class="text-color-2">
			<input type="hidden" id="sliderOutput_zaim_sum" value="" />
			<span class="summ-rubles-zaim" style=" width: 80px;"></span>
			<span class="b-rub">Р</span>
		 </div>

          <div class="clearboth"></div>
         </div>

        <div class="row_calculator">
          <div> <span><img src="<?=SITE_TEMPLATE_PATH?>/images/icon-calculator-time-color-2.png"  /></span> <label class="text-color-5">На срок</label> </div>
          <div class="foundationSlider">
                <div class="divisions-found division-per-found" style="margin-left: 3px; width: 99% !important;">
                      <div class="nick nick_14"> <span class="nick_value">3</span></div>

                      <div class="nick nick_14"> <span class="nick_value">4</span></div>

                      <div class="nick nick_14"> <span class="nick_value">5</span></div>

                      <div class="nick nick_14"> <span class="nick_value">6</span></div>

                      <div class="nick nick_14"> <span class="nick_value">7</span></div>

                      <div class="nick nick_14"> <span class="nick_value">8</span></div>

                      <div class="nick nick_14"> <span class="nick_value">9</span></div>

                      <div class="nick nick_14"> <span class="nick_value">10</span></div>

                      <div class="nick nick_14"> <span class="nick_value">11</span></div>

                      <div class="nick nick_14"> <span class="nick_value">12</span></div>

                      <div class="nick nick_14"> <span class="nick_value">13</span></div>

                      <div class="nick nick_14"> <span class="nick_value">14</span></div>
                      <div class="nick nick_14"> <span class="nick_value">15</span></div>
                      <div class="nick nick_14"> <span class="nick_value">16</span></div>
                      <div class="nick nick_14"> <span class="nick_value">17</span></div>
                      <div class="nick nick_14"> <span class="nick_value">18</span></div>
                </div>
            <div class="range-slider vertical-range round" id="slider_zaim_mounth" data-slider="4" data-options="display_selector: #sliderOutput_zaim_mes; initial: 0; start: 3; end: 18; step: 1;">
			<span class="range-slider-handle border-color-2" role="slider_zaim_mounth" tabindex="0" aria-labelledby="sliderLabel"></span>
			<span class="range-slider-active-segment background-color-5"></span>
			<input type="hidden" />
			</div>
           </div>

          <div class="text-color-2"> <input type="hidden" id="sliderOutput_zaim_mes" value="" /> <span class="summ-mounth-zaim"></span> <span class="word-month-zaim"></span> </div>

          <div class="clearboth"></div>
         </div>

        <div class="info">
          <p style="display:inline-block; width: 30%; float:left;     padding-left: 0px;"> <span class="text-color-2" style="font-size: 15px; margin-bottom: 10px;"> <span id="fond_percent_rate_on_day" style="font-size: 15px;">0,16</span>%</span>
            <br />
          процентная ставка
            <br />
          в 1 календарный<br />день </p>

		  <h3 style="display:inline-block; width: 40%; float:left; font-size: 15px; margin-top: 20px;">Вы получаете <span class="summ-rubles-zaim">2 000 000</span> рублей на <span class="amount_month">4</span> <span class="word-month">мес.</span></h3>

          <p style="display:inline-block; width: 20%; float:left;     text-align: left; margin-left: 49px;">

			<span class="text-color-2" style=" margin-bottom: 10px;">
				<span id="calc_monthly_payment_zaim" style="color: #32a2cf; font-size: 15px;"></span><span class="b-rub" style="color: #32a2cf; font-size: 15px;">Р</span>
			</span>
			<br />
			составит вам<br /> ежемесячный платеж
            <br />
			</p>
         </div>

       <a href="#bl_modal_zaem" role="button" data-toggle="modal"  class="clear-both b-green-butt block-center uppercase" style="margin-bottom: 31px;" >Подать заявку</a> </div>

        <?/*
		<form name="cl_form" id="ClForm" role="form" action="" method="">
        <div class="ui_slider">
          <div class="slider-summa">
                <div class="divisions-found hidden-xs" style="margin-bottom: 5px;">
                  <div class="divide divide_50"> <span class="divide_value">300 тыс.</span> </div>

                  <div class="divide divide_50"> <span class="divide_value">650 тыс.</span> </div>

                  <div class="divide divide_50"> <span class="divide_value">1 млн.</span> </div>
                </div>
			 <img src="<?=SITE_TEMPLATE_PATH?>/images/DZ_icon_cal-1.png"  />
			 <label for="amount">Сумма</label>
            <div id="slider-range-min"></div>
           			 <input type="hidden" id="amount_input" onchange="calc(this.value);" />
                    <span class="amount_rubles rez_amount" id="">2 000 000</span>
                    <span class="word-rubles">руб.</span>
          </div>

          <div class="slider-days"> 				 <img src="<?=SITE_TEMPLATE_PATH?>/images/DZ_icon_cal-2.png"  /> 				 <label for="amount2">На срок</label>
            <div id="slider-range-min2"></div>
           				 <input type="hidden" id="amount2_input" onchange="calc(this.value);" /> 				 <span class="amount_month rez_amount" id="start_month">22</span> 				 <span class="word-month">мес.</span> 				</div>

             <div class="clearboth"></div>

                <div class="divisions-found division-per-found hidden-xs" style="margin-left: 3px; width: 99% !important;">
                  <div class="nick nick_14"> <span class="nick_value">3</span></div>

                  <div class="nick nick_14"> <span class="nick_value">4</span></div>

                  <div class="nick nick_14"> <span class="nick_value">5</span></div>

                  <div class="nick nick_14"> <span class="nick_value">6</span></div>

                  <div class="nick nick_14"> <span class="nick_value">7</span></div>

                  <div class="nick nick_14"> <span class="nick_value">8</span></div>

                  <div class="nick nick_14"> <span class="nick_value">9</span></div>

                  <div class="nick nick_14"> <span class="nick_value">10</span></div>

                  <div class="nick nick_14"> <span class="nick_value">11</span></div>

                  <div class="nick nick_14"> <span class="nick_value">12</span></div>

                  <div class="nick nick_14"> <span class="nick_value">13</span></div>

                  <div class="nick nick_14"> <span class="nick_value">14</span></div>
                  <div class="nick nick_14"> <span class="nick_value">15</span></div>
                  <div class="nick nick_14"> <span class="nick_value">16</span></div>
                  <div class="nick nick_14"> <span class="nick_value">17</span></div>
                  <div class="nick nick_14"> <span class="nick_value">18</span></div>
                </div>


          <div class="info">
            <p>
			<span id="percent_rate_on_day">0,16</span>%
              <br />
            процентная ставка
              <br />
            в 1 календарный день
			</p>

			<h3 style="width:40%">Вы получаете <span class="amount_rubles">2 000 000</span> рублей на <span class="amount_month">4</span> <span class="word-month">мес.</span></h3>

            <p> 				 ваш ежемесячный
              <br />
            платеж составит
              <br />
            <span id="monthly_payment"></span><span class="b-rub">Р</span>
			</p>
           				</div>


         			 			<a href="/zaym/#order_form" class="clear-both button button-style-2 block-center uppercase" >Подать заявку</a> 						</div>
      </form>
    */?>
    </div>
</div>
	</div>

 </div>


<!-- <div id="bl_get_funding">
    <a name="order_form"></a>
    <div class="container t_fixed-body-width">
        <img src="/bitrix/templates/delzaem/images/DZ_icon_mini_advantages-9.png"  />
        <h2>Время получить финансирование</h2>
        <div id="bl_form">
        
    </div>
</div> -->

<div class="hidden">

    <div id="bl_check_status">
        <div class="container"><img src="/bitrix/templates/delzaem/images/DZ_icon_mini_advantages-10.png"  /><a class="bl_btn_check_status" href="/status/index.php" >Проверить статус заявки</a>
            <br />
            <a data-target="#bl_modal" data-toggle="modal" href="#about_us" >Кратко о микрозаймах</a></div>
    </div>

</div>

<div id="bl-advantages-of-microfinance" class="container-fluid" style="margin-top: 50px;">

	<div class="container t_fixed-body-width">

		<div class="text-center">

			<table class="block-center" cellspacing="0" cellpadding="0" border="0">
				<tbody>
					<tr>
						<td class="strip-left-tail"></td>
						<td class="strip-body">
							  ПРЕИМУЩЕСТВА МИКРОФИНАНСИРОВАНИЯ
						</td>
						<td class="strip-right-tail"></td>
					</tr>
				</tbody>
			</table>
			<br/><br/>
		</div>

		<div class="clearfix">

			<div class="col-xs-12 col-sm-12 b-procs-1">

				<div class="col-xs-2 col-sm-2 text-center">

					<br/>

					<img src="<?=SITE_TEMPLATE_PATH?>/images/b-circ-1.png" alt=""><br/><br/>

					<span class="text-color-1">0,08%</span><br/><br/>

					Привлекательные<br>процентные<br>ставки

					<br/>

				</div>

				<div class="col-xs-2  col-sm-2 text-center">

					<br/>

					<img src="<?=SITE_TEMPLATE_PATH?>/images/b-circ-2.png" alt=""><br/><br/>

					<span class="text-color-1">от 1 часа</span><br/><br/>

					Срок<br>рассмотрения<br>заявки при<br>полном пакете<br>документов до 3-х<br>рабочих дней

					<br/>

				</div>

				<div class="col-xs-2  col-sm-2 text-center">

					<br/>

					<img src="<?=SITE_TEMPLATE_PATH?>/images/b-circ-2.png" alt=""><br/><br/>

					<span class="text-color-1">Менеджер</span><br/><br/>

					Персональный кредитный специалист

					<br/>

				</div>

				<div class="col-xs-2 col-sm-2 text-center">

					<br/>

					<img src="<?=SITE_TEMPLATE_PATH?>/images/b-circ-3.png" alt=""><br/><br/>

					<span class="text-color-1">Удобно</span><br/><br/>

					Предлагаем заемные средства без залога на различные сроки<br>- от 3-х месяцев до 18-ти месяцев

					<br/>

				</div>

				<div class="col-xs-2 col-sm-2 text-center">

					<br/>

					<img src="<?=SITE_TEMPLATE_PATH?>/images/b-circ-4.png" alt=""><br/><br/>

					<span class="text-color-1">График</span><br/><br/>

					Индивидуальный график<br>платежей

					<br/>

				</div>

			</div>

		</div>

	</div>

</div>

<div id="bl-repayment" class="container-fluid" style="margin-top: 50px;">

	<div class="container t_fixed-body-width">

		<div class="text-center">

			<div class="background-color-3" style="display:inline-block;padding-left:70px;padding-right:70px;padding-top:30px;padding-bottom:30px;font-size: 16px; color: #636363;">
				Погашение происходит путем безналичного перевода<br>денежных средств на расчетный счет
			</div>

		</div>

	</div>

</div>

<div class="b-celi">
	<div class="container t_fixed-body-width">
		<img src="/bitrix/templates/delzaem/images/b-celi-main.png" alt="" class="b-celi-main">
		<h3>ЦЕЛИ И ПРЕДНАЗНАЧЕНИЕ ЗАЙМА</h3>
		<ul>
			<li>
				<img src="/bitrix/templates/delzaem/images/b-celi-1.png" alt="">
				<p>Пополнение<br>оборотных<br>средств</p>
			</li>
			<li>
				<img src="/bitrix/templates/delzaem/images/b-celi-2.png" alt="">
				<p>Кассовый<br>разрыв</p>
			</li>
			<li>
				<img src="/bitrix/templates/delzaem/images/b-celi-3.png" alt="">
				<p>Покупка<br>недвижимости или<br>транспортных<br>средств</p>
			</li>
			<li>
				<img src="/bitrix/templates/delzaem/images/b-celi-4.png" alt="">
				<p>Покупка<br>оборудования</p>
			</li>
			<li>
				<img src="/bitrix/templates/delzaem/images/b-celi-5.png" alt="">
				<p>Целевые<br>займы</p>
			</li>
		</ul>
	</div>
</div>

<div id="bl-conditions-and-requirements" class="container-fluid" style="margin-top: 50px;">

	<div class="container t_fixed-body-width">

			<div class="col-xs-10 col-sm-12 b-usl" >

				<div id="bl-conditions" class="col-xs-6 col-sm-6">
					<h2 class="text-color-2">УСЛОВИЯ</h2>
					<ul>
						<li><span class="text-color-2">Процентная ставка</span> — 0,08% в 1 календарный день</li>
						<li><span class="text-color-2">Валюта займа</span> — рубли РФ</li>
						<li><span class="text-color-2">Минимальная сумма займа</span> — 300 000<span class="b-rub">Р</span></li>
						<li><span class="text-color-2">Максимальная сумма займа</span> — 1 000 000<span class="b-rub">Р</span></li>
						<li>Минимальный и максимальный <span class="text-color-2">срок займа</span> от 3-х до 18-ти месяцев</li>
						<li><span class="text-color-2">Комиссия за выдачу и обслуживание займа</span> — не взимается</li>
					</ul>
				</div>

				<div id="bl-requirements" class="col-xs-6 col-sm-6">
					<h2 class="text-color-1">ТРЕБОВАНИЯ К ЗАЕМЩИКУ</h2>
					<ul>
						<li>Личное поручительство <br/><span class="text-color-1">двух физических лиц</span><br></li>
						<li><span class="text-color-1">Опыт работы</span> заемщика <br/>в данной сфере не менее 5 лет<br></li>
						<li>Заемщик <span class="text-color-1">не имеет задолженностей</span> <br/>перед налоговой инспекцией<br/></li>
					</ul>
					<a data-target="#bl_modal_2" style="    margin-top: 20px;display: inline-block;" data-toggle="modal" href="#about_us" class="b-list-link"><img src="/bitrix/templates/delzaem/images/b-list-link.png" alt=""><span>Подробнее</span></a>
					<!-- <a data-target="#bl_modal_2" data-toggle="modal" href="#about_us">Шаблон<br/>договора</a> -->
				</div>

			</div>

	</div>

</div>

<div id="bl-how-it-works" class="container-fluid background-color-2" style="margin-top: 50px;">

	<div class="container t_fixed-body-width">


			<div class="col-xs-12 col-sm-12">

				<div class="col-xs-12 col-sm-12">

					<img class="icon-top-center" src="<?=SITE_TEMPLATE_PATH?>/images/icon-how-it-works-top-center.png"/>

					<h2 class="text-color-1" style="font-size: 22px;color: #286aa6 !important;margin-top: 30px;margin-bottom: 50px;">КАК ВСЕ РАБОТАЕТ</h2>

				</div>

				<div class="col-xs-12 col-sm-12 b-etaps b-etaps-two">

					<div class="col-xs-4 col-sm-4 text-center">

						<img src="/bitrix/templates/delzaem/images/icon-how-it-works-request.png" />

						<br/><br/>

						<span class="text-color-1">Заполнить<br>заявку</span>

						<br/><br/>

						<span class="text-color-3">
							Заполнение заявки займет не более 20 минут. При необходимости Вы можете воспользоваться помощью менеджера.
						</span>

						<br/><br/>

					</div>

					<div class="col-xs-4 col-sm-4 text-center">

						<img src="/bitrix/templates/delzaem/images/icon-how-it-works-approving.png" />

						<br/><br/>

						<span class="text-color-1">Дождаться<br>подтверждения</span>

						<br/><br/>

						<span class="text-color-3">
							В течении 1-2 рабочих дней представители компании свяжутся с Вами по результатам проведенной проверки.
						</span>

						<br/><br/>

					</div>

					<div class="col-xs-4  col-sm-4 text-center">

						<img src="/bitrix/templates/delzaem/images/icon-how-it-works-earnings.png" />

						<br/><br/>

						<span class="text-color-1">Получить<br>деньги</span>

						<br/><br/>

						<span class="text-color-3">
							Вам остается только приехать в офис, подписать договоры и получить финансы на расчетный счет компании.
						</span>

						<br/><br/>

					</div>

				</div>

			</div>

		</div>

</div>

<div id="bl_there_is_no_time" class="container-fluid background-color-1 " style="margin-top: 70px;">

	<div class="container t_fixed-body-width">

			<div class="col-xs-12 col-sm-12 text-center">

				<table class="block-center" cellspacing="0" cellpadding="0" border="0">

					<tr>

						<td class="strip-left-tail"></td>

						 <td class="strip-body">
							  НЕТ ВРЕМЕНИ?
						 </td>

						<td class="strip-right-tail"></td>

					</tr>

				</table>
				
				<img class="icon-top-center" style="margin-top: 55px; margin-bottom: 30px;" src="<?=SITE_TEMPLATE_PATH?>/images/icon-no-time-top-center.png"/>

				<p class="medium" style="font-size: 18px;">
					Для Вашего удобства оставьте контактную<br />информацию, мы Вам перезвоним и сами<br />заполним заявку на займ.
				</p>

				<button class="block-center request-recall-module-open-link b-green-butt" style="cursor: pointer; background-color: transparent; padding: 9px;" >ЗАЯВКА НА ЗВОНОК</button>

			</div>

	</div>

 </div>
 <div class=" ">
<?$APPLICATION->IncludeComponent(
	"bitrix:news",
	"additional_documents",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "block_site_content",
		"IBLOCK_ID" => "9",
		"NEWS_COUNT" => "100",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"USE_FILTER" => "N",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ID",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_ELEMENT_CHAIN" => "N",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(0=>"NAME",1=>"download_file",2=>"",),
		"LIST_PROPERTY_CODE" => array(0=>"download_file",1=>"",),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(0=>"",1=>"",),
		"DETAIL_PROPERTY_CODE" => array(0=>"",1=>"",),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"VARIABLE_ALIASES" => Array(
			"SECTION_ID" => "SECTION_ID",
			"ELEMENT_ID" => "ELEMENT_ID"
		)
	)
);?>
</div>

<div class="container-fluid" style="margin-top: 50px;">

 <?$APPLICATION->IncludeComponent(
	"bitrix:news",
	"help",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "block_site_content",
		"IBLOCK_ID" => "10",
		"NEWS_COUNT" => "200",
		"USE_SEARCH" => "N",
		"USE_RSS" => "N",
		"USE_RATING" => "N",
		"USE_CATEGORIES" => "N",
		"USE_REVIEW" => "N",
		"USE_FILTER" => "N",
		"SORT_BY1" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_BY2" => "ACTIVE_FROM",
		"SORT_ORDER2" => "DESC",
		"CHECK_DATES" => "Y",
		"SEF_MODE" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_ELEMENT_CHAIN" => "N",
		"USE_PERMISSIONS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"LIST_FIELD_CODE" => array(0=>"NAME",1=>"PREVIEW_TEXT",2=>"",),
		"LIST_PROPERTY_CODE" => array(0=>"",1=>"",),
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"DISPLAY_NAME" => "N",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
		"DETAIL_FIELD_CODE" => array(0=>"",1=>"",),
		"DETAIL_PROPERTY_CODE" => array(0=>"",1=>"",),
		"DETAIL_DISPLAY_TOP_PAGER" => "N",
		"DETAIL_DISPLAY_BOTTOM_PAGER" => "N",
		"DETAIL_PAGER_TITLE" => "Страница",
		"DETAIL_PAGER_TEMPLATE" => "",
		"DETAIL_PAGER_SHOW_ALL" => "N",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"USE_SHARE" => "N",
		"VARIABLE_ALIASES" => Array(
			"SECTION_ID" => "SECTION_ID",
			"ELEMENT_ID" => "ELEMENT_ID"
		)
	)
);?>

</div>

<div class="b-check-status">
	<div class="container t_fixed-body-width">
		<a href="/status/" class="btn b-blue-button">ПРОВЕРИТЬ СТАТУС ЗАЯВКИ</a>
		<!-- <h3>ПРОВЕРИТЬ СТАТУС ЗАЯВКИ</h3>
		<input type="text" name="phone" class="phone-mask" placeholder="Введите номер телефона" required>
		<a href="#">ПРОВЕРИТЬ</a> -->
	</div>
</div>
<div class="b-questions">
	<h3>Если у Вас возникли вопросы,<br>Вы всегда можете их задать по телефону</h3>
	<h2>+7 499 929-81-72</h2>
</div>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

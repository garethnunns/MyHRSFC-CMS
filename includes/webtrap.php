    <?php
    /**
    WebTrap Interface Script
    Generated 2013-10-13 00:47:18
    --
    This script is provided AS IS without any warranty whatsoever.
    In no event will Webtrap Ltd or its suppliers be liable for any lost
    revenue, profit, or data, or for special, indirect, consequential,
    incidental, or punitive damages however caused and regardless of the
    theory of liability arising out of the use of or inability to use the
    services even if Webtrap Ltd or its suppliers have been advised of the
    possibility of such damages.
    --
    Sends details to WebTrap interface and retrieves a return code
    **/
    $_wt_req = 'auth=6612cd0e0ef2beab483ca6ed0df59c3943dd14bf'; // Authentication key can be generated in the Authentication section on login
    $_wt_input['ip'] = $_SERVER['REMOTE_ADDR'];
    $_wt_input['page'] = $_SERVER['REQUEST_URI'];
    $_wt_input['useragent'] = $_SERVER['HTTP_USER_AGENT'];
    $_wt_input['referer'] = $_SERVER['HTTP_REFERER'];
    $_wt_input['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $_wt_input['status'] = 200; // HTTP Status Code. Accepts 300 to 408 (inclusive) and 400 to 410 (inclusive). Otherwise assume 200.
    $_wt_input['action'] = 0; // 0 is normal page visit. See API Docs for more details.
    foreach ($_wt_input as $key => $value) $_wt_req .= '&'.$key.'='.urlencode(stripslashes($value));
    $_wt_ch = curl_init();
    curl_setopt($_wt_ch, CURLOPT_URL, 'srv0.webtrap.co.uk/interface.php');
    curl_setopt($_wt_ch, CURLOPT_POST, count($_wt_input)+1);
    curl_setopt($_wt_ch, CURLOPT_POSTFIELDS, $_wt_req);
    curl_setopt($_wt_ch, CURLOPT_RETURNTRANSFER, true);
    // If you find that the connection to Webtrap takes too long and slows your website down, then please inform us.
    if(version_compare(PHP_VERSION, '5.2.3') >= 0) curl_setopt($_wt_ch, 156, 500); // 0.5 seconds MAX
    else curl_setopt($_wt_ch, CURLOPT_CONNECTTIMEOUT, 1); // 1 second MAX, versions below 5.2.3 do not support CURLOPT_CONNECTTIMEOUT_MS and value must be int
    $_wt_res = curl_exec($_wt_ch);
    curl_close($_wt_ch);
    $_wt_arr = explode(':', $_wt_res);
    if($_wt_arr[0] == 10 || $_wt_arr[1] == 2) // BAD visitor OR DDOS Attack!!
    {
        header('HTTP/1.0 403 Forbidden');
        die('<div style="width: 400px;margin-top: 80px;margin-bottom: 80px;margin-left: auto;margin-right: auto;font-size: 2em;"><p>Access Denied</p><p style="text-align: right" dir="rtl">????? ?????</p><p>?????</p><p>?????? ????????</p><p style="text-align: right" dir="rtl">??? ??????</p><p>Zugriff verweigert</p><p>Acceso denegado</p><p>?? ??</p></div>');
    }
    else if($_wt_arr[1] == 1) // DDOS Attack! Temporarily Unavailable
    {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 300');
        die('<!DOCTYPE html><html><head><title>Temporarily unavailable</title><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"></head><body><div style="width: 400px;margin-top: 80px;margin-bottom: 80px;margin-left: auto;margin-right: auto;font-size: 2em;"><p>Temporarily unavailable</p><p style="text-align: right" dir="rtl">???? ????? ????</p><p>?????</p><p>???????? ??????????</p><p style="text-align: right" dir="rtl">????? ?????</p><p>Vorübergehend nicht verfügbar</p><p>Temporalmente no disponible</p><p>? ????? ??? ? ????</p></div></body></html>');
    }
    else if($_wt_arr[0] == 9 || $_wt_arr[0] == 8) // Codes 8 and 9 should not be allowed in!
    {
        header('HTTP/1.0 403 Forbidden');
        die('<!DOCTYPE html><html><head><title>Access Denied</title><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"><style type="text/css">p{margin: 0;padding-bottom: 6px;}</style></head><body><div style="width: 400px;margin-top: 80px;margin-bottom: 80px;margin-left: auto;margin-right: auto;font-size: 2em;"><p>Access Denied</p><p style="font-size: 13px;">Your IP Address has been marked as unsafe</p><p style="text-align: right" dir="rtl">????? ?????</p><p style="font-size: 13px;text-align: right" dir="rtl">????? ?-IP ??? ????? ?????</p><p>?????</p><p style="font-size: 13px;">??IP??????????</p><p>?????? ????????</p><p style="font-size: 13px;">??? IP-????? ??? ??????? ??? ????????????</p><p style="text-align: right" dir="rtl">??? ??????</p><p style="font-size: 13px;text-align: right" dir="rtl">?? ??? ????? ????? ???????? ????????? ??? ??????</p><p>Zugriff verweigert</p><p style="font-size: 13px;">Ihre IP Adresse hat als unsicher markiert</p><p>Acceso denegado</p><p style="font-size: 13px;">Tu dirección IP ha sido marcado como inseguro</p><p>?? ??</p><p style="font-size: 13px;">??? IP ??? ???? ?? ??? ??????</p><hr style="height: 1px;margin-top: 10px;margin-bottom: 15px;border: none;background-color: #000000;"/><p style="font-size: 13px;"> Is this a mistake? <a href="https://www.webtrap.co.uk/removal">Request a removal</a></p><p style="font-size: 13px;direction: rtl;text-align: right;" dir="rtl"> ??? ?? ????? <a href="https://www.webtrap.co.uk/removal?lang=he">???? ????</a></p><p style="font-size: 13px;"> ??????, <a href="https://www.webtrap.co.uk/removal?lang=zh">????</a></p><p style="font-size: 13px;"> ???????? ?? ??? ???????? <a href="https://www.webtrap.co.uk/removal?lang=ru">????????? ????????</a></p><p style="font-size: 13px;direction: rtl;text-align: right;" dir="rtl"> ?? ??? ???? <a href="https://www.webtrap.co.uk/removal?lang=ar">??? ?????</a></p><p style="font-size: 13px;"> Ist das ein Fehler? <a href="https://www.webtrap.co.uk/removal?lang=de">anfordern Entfernen</a></p><p style="font-size: 13px;"> ¿Es esto un error? <a href="https://www.webtrap.co.uk/removal?lang=es">Solicite una eliminación</a></p><p style="font-size: 13px;"> ? ?????? <a href="https://www.webtrap.co.uk/removal?lang=ko">?? ??</a></p></div></body></html>');
    }
    /*
    else if($_wt_arr[0] == 7)
    { // Recommended Action: Protect website with CAPTCHA, hide email addresses, prevent/limit form use.
    }
    else if($_wt_arr[0] == 6 || $_wt_arr[0] == 5)
    { // Recommended Action: Protect forms with CAPTCHA, hide email addresses.
    }
    else if($_wt_arr[0] == 4 || $_wt_arr[0] == 3)
    { // Recommended Action: Little action.
    }
    */
    ?>
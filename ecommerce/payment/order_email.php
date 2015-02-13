<?php
$message='<div> <b>Order# '.$insertedId1.'</b> <br>
  <br>
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
    <tbody>
      <tr>
        <td> Thank you for your order! <br>
          <br>
          Your order details are below: <br>
          <br></td>
      </tr>
      <tr>
        <td><table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tbody>
              <tr>
                <td colspan="4"><table>
                   <tbody>
                      <tr>
                        <td width="25%" valign="top"><table width="100%">
                            <tbody>
                              <tr>
                                <td nowrap="" colspan="2">&nbsp;BILLING INFORMATION&nbsp; </td>
                              </tr>
                              <tr>
                                <td></td>
                              </tr>
                              <tr>
                                <td><b>Payment Information</b><br></td>
								</tr>
								<tr>
								  <td>
                                  '.$CreditCard0CardType.'<br>
                                  '.$Contact0FirstName.' '.$Contact0LastName.'<br>
                                  '.$Contact0StreetAddress1.' <br>
                                  '.$Contact0City.',&nbsp;'.$Contact0State.'&nbsp;'.$Contact0PostalCode.'<br>
                                  '.$Contact0Country.'<br>
                                  <br>
                                  '.$Contact0Phone2.'<br></td>
                              </tr>
                            </tbody>
                          </table></td>
                        <td valign="top"><img width="1" height="275"></td>
                        <td width="20%" valign="top"><table width="100%">
                            <tbody>
                              <tr>
                                <td nowrap="">&nbsp;SHIPPING INFORMATION&nbsp; </td>
                              </tr>
                              <tr>
                                <td></td>
                              </tr>
                              <tr>
                                <td><b>Shipping Address</b> <br></td>
                              </tr>
                              <tr>
                                <td> '.$Contact0FirstName.' '.$Contact0LastName.'<br>
                                  '.$Contact0StreetAddress1.' <br>
                                  '.$Contact0City.',&nbsp;'.$Contact0State.'&nbsp;'.$Contact0PostalCode.'<br>
                                  '.$Contact0Country.'<br>
                                  <br>
                                  '.$Contact0Phone2.'<br></td>
                              </tr>
                            </tbody>
                          </table></td>
                        <td valign="top"><img width="1" height="275"></td>
                        <td width="100%" valign="top"><table width="100%">
                            <tbody>
                              <tr>
                                <td nowrap="" colspan="3">&nbsp;ORDER SUMMARY&nbsp; </td>
                              </tr>
                              <tr>
                                <td></td>
                              </tr>
                              <tr>
                                <td valign="top"><table width="100%" cellspacing="2">
                                    <tbody>
                                      <tr>
                                        <td width="15%"><b>Order Items</b></td>
										 <td width="15%"> 1 </td>
                                      </tr>
                                      <tr>
                                        <td width="25%"> <br>
                                          <b>Description:</b>&nbsp;'.$_POST['Description'].' </td>
                                        <td width="35%"><b>Todays Order :</b> $'.number_format($totalAmtPaid,2).'<br>
										<b>Subscription Order :</b> $'.number_format($GetSubscriptionRecords['SubscriptionPrice'],2).' / month </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td width="15%"> <b>Sub Total:</b> </td>
                                        <td width="10%"> $'.number_format($totalAmtPaid,2).' </td>
                                      </tr>
                                      <tr>
                                        <td width="15%"><b>Order Total:</b></td>
                                        <td width="10%">$'.number_format($totalAmtPaid,2).'</td>
                                      </tr>
                                      <tr>
                                        <td colspan="3">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td width="15%"><b>Payment Summary</b></td>
                                        <td width="10%"><b>Amount</b></td>
                                      </tr>
                                      <tr>
                                        <td width="15%"> Today </td>
                                        <td width="10%"> $'.number_format($totalAmtPaid,2).' </td>
                                      </tr>
                                    </tbody>
                                  </table></td>
                              </tr>
                            </tbody>
                          </table></td>
                      </tr>
                    </tbody>
                  </table></td>
              </tr>
            </tbody>
          </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
    </tbody>
  </table>
  <br>
  <br>
</div>';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: Support <support@the220companies.com>' . "\r\n";
$subject = 'Receipt For Your Purchase';

mail($Contact0Email,$subject,$message,$headers);

?>
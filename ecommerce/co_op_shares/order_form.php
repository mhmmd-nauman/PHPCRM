<?php require_once "../../lib/include.php";
$utilObj = new util();
//print_r($_SESSION);
if(isset($_SESSION['loggedInAs']))
{
	$member_details=$utilObj->getSingleRow('Member','ID='.$_SESSION['loggedInAs']);
}

if(isset($_GET['product_id']))
{
	$product_record=$utilObj->getSingleRow('Product','ID='.$_GET['product_id']);
	//print_r($product_record);
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php if($product_record){ echo $product_record['ProductName']; } ?></title>
</head>

<body>
<form  method="Post" action="https://themillionaireos.com/admintti/ecommerce/payment/ProductSubscriptionTemp.php" id="orderForm" target="_top">
  
  
  <table cellspacing="0" cellpadding="3" class="sale-table">
    <tbody>
      <tr>
        <td colspan="99"><style>
  <!--
  .sale-header{
  background: none repeat scroll 0 0 #316398;
  color: #FFFFFF;
  font-family: Arial;
  font-size: 16px;
  font-weight: bold;
  padding: 4px;
  }
  .sale-label-req input[type="text"], .sale-label input[type="text"], .inf-select, .sale-text-req, .sale-text {
  border: 1px solid #666666;
  color: #545454;
  font-family: Arial,Helvetica,sans-serif;
  font-size: 14px;
  font-weight: normal;
  padding: 3px;
  width: 300px;
  }
  .sale-label-req, .sale-label {
  color: #545454;
  font-family: Arial,Helvetica,sans-serif;
  font-size: 16px;
  font-weight: bold;
  padding: 3px 10px;
  }
  .sale-table {
  width: 630px;
  }
  #Order {
    padding: 5px;
    width: 150px;
}-->
</style></td>
      </tr>
      <tr>
        <td height="10" colspan="99"></td>
      </tr>
      <tr>
        <td class="sale-header" colspan="99">Contact Information</td>
      </tr>
   
    <tr>
      <td class="sale-label-req">First Name *</td>
      <td class="sale-label-req"><input type="text" value="<?php if($member_details){ echo $member_details['FirstName']; } ?>" name="Contact0FirstName"></td>
    </tr>
    <tr>
      <td class="sale-label-req">Last Name *</td>
      <td class="sale-label-req"><input type="text" value="<?php if($member_details){ echo $member_details['Surname']; } ?>" name="Contact0LastName"></td>
    </tr>
    <tr>
      <td class="sale-label-req">Email *</td>
      <td class="sale-label-req"><input type="text" value="<?php if($member_details){ echo $member_details['Email']; } ?>" name="Contact0Email" readonly="readonly"></td>
    </tr>
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td class="sale-header" colspan="99">Billing Address</td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">Street Address 1 <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="Contact0StreetAddress1_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" name="Contact0StreetAddress1" class="default-input sale-text-req" size="35" id="Contact0StreetAddress1" value="<?php if($member_details){ echo $member_details['Address']; } ?>"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label">Street Address 2</td>
      <td valign="top" align="left" id="Contact0StreetAddress2_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" name="Contact0StreetAddress2" value="<?php if($member_details){ echo $member_details['AddressLine2']; } ?>" class="default-input sale-text" size="35" id="Contact0StreetAddress2"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">City <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="Contact0City_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" name="Contact0City" value="<?php if($member_details){ echo $member_details['City']; } ?>" class="default-input sale-text-req" size="25" id="Contact0City"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">State <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="Contact0State_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" value="<?php if($member_details){ echo $member_details['member_state']; } ?>" maxlength="2" name="Contact0State" class="default-input sale-text-req" size="2" id="Contact0State"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">Postal Code <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="Contact0PostalCode_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" name="Contact0PostalCode" value="<?php if($member_details){ echo $member_details['member_zip']; } ?>" class="default-input sale-text-req" size="15" id="Contact0PostalCode">
              <input type="hidden" name="Contact0Phone2" id="Contact0Phone2" value="<?php if($member_details){ echo $member_details['Phone']; } ?>" />
              </td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label">Country</td>
      <td valign="top" align="left" id="Contact0Country_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><select name="Contact0Country" id="Contact0Country" class="inf-select default-input sale-text">
                  <option value="">Please select a country</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="American Samoa">American Samoa</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Anguilla">Anguilla</option>
                  <option value="Antarctica">Antarctica</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Aruba">Aruba</option>
                  <option value="Australia">Australia</option>
                  <option value="Austria">Austria</option>
                  <option value="Åland Islands">Åland Islands</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bermuda">Bermuda</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Bouvet Island">Bouvet Island</option>
                  <option value="Brazil">Brazil</option>
                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Cape Verde">Cape Verde</option>
                  <option value="Cayman Islands">Cayman Islands</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Christmas Island">Christmas Island</option>
                  <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Democratic Republic Of Congo">Democratic Republic Of Congo</option>
                  <option value="Cook Islands">Cook Islands</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Côte D'Ivoire">Côte D'Ivoire</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominica">Dominica</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Falkland Islands">Falkland Islands</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France">France</option>
                  <option value="French Guiana">French Guiana</option>
                  <option value="French Polynesia">French Polynesia</option>
                  <option value="French Southern Territories">French Southern Territories</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Greenland">Greenland</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guadeloupe">Guadeloupe</option>
                  <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guernsey">Guernsey</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guinea-Bissau">Guinea-Bissau</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
                  <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran">Iran</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Isle of Man">Isle of Man</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jersey">Jersey</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="North Korea">North Korea</option>
                  <option value="South Korea">South Korea</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Laos">Laos</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libya">Libya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macao">Macao</option>
                  <option value="Republic of Macedonia">Republic of Macedonia</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Martinique">Martinique</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mayotte">Mayotte</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Federated States of Micronesia">Federated States of Micronesia</option>
                  <option value="Moldova">Moldova</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montenegro">Montenegro</option>
                  <option value="Montserrat">Montserrat</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                  <option value="New Caledonia">New Caledonia</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Niger">Niger</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="Niue">Niue</option>
                  <option value="Norfolk Island">Norfolk Island</option>
                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Palestine">Palestine</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Pitcairn">Pitcairn</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Puerto Rico">Puerto Rico</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Romania">Romania</option>
                  <option value="Russian Federation">Russian Federation</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="Réunion">Réunion</option>
                  <option value="St. Barthélemy">St. Barthélemy</option>
                  <option value="St. Helena, Ascension and Tristan Da Cunha">St. Helena, Ascension and Tristan Da Cunha</option>
                  <option value="St. Kitts And Nevis">St. Kitts And Nevis</option>
                  <option value="St. Lucia">St. Lucia</option>
                  <option value="St. Martin">St. Martin</option>
                  <option value="St. Pierre And Miquelon">St. Pierre And Miquelon</option>
                  <option value="St. Vincent And The Grenedines">St. Vincent And The Grenedines</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Serbia">Serbia</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovakia">Slovakia</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Svalbard And Jan Mayen">Svalbard And Jan Mayen</option>
                  <option value="Swaziland">Swaziland</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                  <option value="Taiwan">Taiwan</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania">Tanzania</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Timor-Leste">Timor-Leste</option>
                  <option value="Togo">Togo</option>
                  <option value="Tokelau">Tokelau</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="Uganda">Uganda</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option selected="selected" value="United States">United States</option>
                  <option value="US Minor Outlying Islands">US Minor Outlying Islands</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Viet Nam">Viet Nam</option>
                  <option value="Virgin Islands, British">Virgin Islands, British</option>
                  <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                  <option value="Wallis and Futuna">Wallis and Futuna</option>
                  <option value="Western Sahara">Western Sahara</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>
                </select></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td class="sale-header" colspan="99">Credit Card Information</td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">Card Type <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="CreditCard0CardType_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><select name="CreditCard0CardType" id="CreditCard0CardType" class="inf-select default-input sale-select-req">
                  <option value="">Please select a card type *</option>
                  <option value="American Express">American Express</option>
                  <option value="Discover">Discover</option>
                  <option value="MasterCard">MasterCard</option>
                  <option value="Visa">Visa</option>
                </select></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">Card Number <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="CreditCard0CardNumber_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" autocomplete="off" name="CreditCard0CardNumber" class="default-input sale-text-req" size="35" maxlength="16" id="CreditCard0CardNumber"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">Expiration Month <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="CreditCard0ExpirationMonth_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><select name="CreditCard0ExpirationMonth" id="CreditCard0ExpirationMonth" class="inf-select default-input sale-select-req">
			  <?php 
			  //code for gnerating months
			  for($i=1;$i<=12;$i++){ 
			  if($i<10)
				$month = "0$i"; // add the zero
				else
				$month = "$i"; // don't add the zero
				?>
              <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
              <?php } ?>
                  
                </select></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">Expiration Year <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="CreditCard0ExpirationYear_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><select name="CreditCard0ExpirationYear" id="CreditCard0ExpirationYear" class="inf-select default-input sale-select-req">
              <?php //code for generating year
			  (int)$curr_year=date('Y');
			  for($i=$curr_year;$i<=$curr_year+15;$i++){ ?>
              <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
              <?php } ?>
                  
                </select></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" nowrap="nowrap" class="sale-label-req">CVC <span class="sale-req">*</span></td>
      <td valign="top" align="left" id="CreditCard0VerificationCode_data"><table cellspacing="0px" cellpadding="0px" border="0px">
          <tbody>
            <tr>
              <td><input type="text" name="CreditCard0VerificationCode" class="default-input sale-select-req" size="5" id="CreditCard0VerificationCode"></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
    <input type="hidden" value="<?php echo $_GET['product_id']; ?>" name="ProductId" id="ProductId">
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td class="sale-header" colspan="99">Product Purchase Plan</td>
    </tr>
    <tr>
      <td width="100%" colspan="99"><table width="100%" cellspacing="0" cellpadding="4" border="0">
          <tbody>
            <tr>
              <td width="80%" class="sale-productheader"><b><?php if($product_record){ echo $product_record['ProductName']; } ?></b></td>
              <td width="20%" valign="top" class="sale-productheader">Amt</td>
            </tr>
          
          <tr>
            <td><table>
                <tbody>
                  <tr>
                    <td><input type="radio" checked="checked" value="A" name="PurchaseType" class="radio" disabled="disabled" >
                      <input type="hidden" value="<?php if($product_record){ echo $product_record['ProductPrice']; } ?>" name="PayTotal_A" id="PayTotal_A">
                      </td>
                    <td nowrap="nowrap" class="sale-productdata">1 Payment of $<?php if($product_record){ echo $product_record['ProductPrice']; } ?>.00</td>
                  </tr>
                </tbody>
              </table></td>
            <td class="sale-productdata"><b>$<?php if($product_record){ echo $product_record['ProductPrice']; } ?>.00</b></td>
          </tr>
            </tbody>
        </table></td>
    </tr>
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td class="sale-header" colspan="99">Total Amount You Pay Right Now</td>
    </tr>
    <tr>
      <td colspan="99"><div id="orderTotal">
          <table width="100%">
            <tbody>
              <tr>
                <td width="80%"> <?php if($product_record){ echo $product_record['ProductName']; } ?> </td>
                <td width="20%"> $<?php if($product_record){ echo $product_record['ProductPrice']; } ?>.00 </td>
              </tr>
              <tr>
                <td colspan="2"><hr style="width:100%; height: 1px; margin: 0; border: 1px; background-color: #000000; color: #000000"></td>
              </tr>
              <tr>
                <td width="80%"> Total </td>
                <td width="20%"> $<?php if($product_record){ echo $product_record['ProductPrice']; } ?>.00 </td>
              </tr>
            </tbody>
          </table>
        </div>
        </td>
    </tr>
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td align="center" class="sale-header" colspan="99">Process</td>
    </tr>
    <tr>
      <td colspan="99"></td>
    </tr>
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td align="center" colspan="99"></td>
    </tr>
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td align="center" colspan="99">
        </td>
    </tr>
    <tr>
      <td height="10" colspan="99"></td>
    </tr>
    <tr>
      <td align="center" colspan="99"><?php if($product_record){ ?><input type="submit" value="Order" name="Order" class="default-input sale-orderbutton np inf-button" id="Order"><?php } ?></td>
    </tr>
      </tbody>
  </table>
</form>
</body>
</html>
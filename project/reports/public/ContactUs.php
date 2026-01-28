<?php
require_once  '../../config/configPublic.php';
class ContactUs extends ConfigClass
{	
	public $page_token = "feedback_form";
    public function __construct()
    {       
    }
    public function main_content($post_data_array = array())
    {
        $site_data = $this->siteData();
        ob_start();

        // #############

        // PAGE CONTENT START

        // #############
		 $lang_code_2d=$this->getCurrentUserLanguage2D();
        ?>
<script type="text/javascript">
$(document).ready(function() {
    $("#dcode").on('change', function(e) {
        var dcode = $(this).val();
        if (dcode != '') {
            $.ajax({
                type: "POST",
                url: "ContactUs.php",
                data: {
                    "dcode": btoa(dcode),
                    'cmd': btoa(1)
                },
                success: function(data) {
                    if (data != '') {
                        $('#lbcode').html(data);
                        return false;
                    }
                },
                dataType: 'html'
            });
        }
    });
    $("#btnsubmit").on('click', function() {

        var Current_Field_id = $(this).attr('id');
        $('#' + Current_Field_id).hide();
        try {
            if ($("#dcode").val() == '') {
                throw {
                    msg: "Choose District",
                    foc: "#dcode"
                }
            }
            if ($("#lbcode").val() == '') {
                throw {
                    msg: "Choose Town Panchayat",
                    foc: "#lbcode"
                }
            }
            if ($("#taxid").val() == '') {
                throw {
                    msg: "Choose Tax Type",
                    foc: "#taxid"
                }
            }
            if ($("#txtname").val().length == '') {
                throw {
                    msg: "Enter Name",
                    foc: "#txtname"
                }
            }
            if ($("#txtemail").val().length == '') {
                throw {
                    msg: "Enter Email",
                    foc: "#txtemail"
                }
            }
            if ($("#txtmbl").val().length == '') {
                throw {
                    msg: "Enter Mobile Number",
                    foc: "#txtmbl"
                }
            }
            if ($("#txtsubject").val().length == '') {
                throw {
                    msg: "Enter Subject",
                    foc: "#txtsubject"
                }
            }
            if ($("#txtdescription").val().length == '') {
                throw {
                    msg: "Enter Message",
                    foc: "#txtdescription"
                }
            }
            if ($("#txtCaptcha").val().length == '') {
                throw {
                    msg: "Enter Captcha",
                    foc: "#txtCaptcha"
                }
            }
        } catch (e) {
            alert(e.msg);
            $('#' + Current_Field_id).show();
            $(e.foc).focus();
            return false;
        }
        return true;
    });
});
</script>
<style>
.cards {
    padding: 20px;
    margin: 20px;
    border-radius: 7px;
    /* border-top: 7px solid #555a86;
border-bottom: 7px solid #555a86; */
    /* box-shadow: 0 0 8px #333; */
    box-shadow: 3px 3px 10px rgb(0 0 0 / 40%) inset;
    /* box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px; */
    /* border: 10px solid #EBEBEB; */
    background: #fff;
}
</style>
<input type="hidden" id="page_lable_id" name="page_lable_id" value="191" />
<div class="container" style="margin-top:30px">
    <div class="row">

        <div class="col-md-12 col-xs-12">



        </div>
    </div>
    <script>
    function scrollToBottom() {
        window.location.href = "#getintouch";
    }
    </script>
    <style>
    .tndtp_form_table {
        font-size: 15px;
        font-weight: bold;
        width: 100%;
        /* border-collapse: collapse;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden; */
    }

    .tndtp_form_table thead {
        padding: 3px
    }

    .tndtp_form_report_table {
        font-size: 15px;
        font-weight: bold;
        width: 100%;
        border-radius: 10px;
        text-align: center;
    }

    .tndtp_form_report_table th,
    td {
        padding: 10px;
        text-align: center;
    }

    @media (max-width: 600px) {

        .tndtp_form_report_table,
        .tndtp_form_table {
            width: 100%;
            display: block;
            overflow-x: auto;
        }

        /* Display table rows as block elements */
        .tndtp_form_report_table thead,
        .tndtp_form_table thead {
            display: none;
        }
    }

    .newhead {
        background: linear-gradient(to right, #494889, #3B3A7C, #494889);
        color: white;
    }
    </style>



    <?php /*?><h4 class="text-center  m-3"><strong>Directorate of Town Panchayats</strong></h4>
    <h5 class="text-center  m-3"><strong>7th &amp; 8th Floor,Urban Administrative Office Campus, Chennai – 600
            028.</strong></h5><?php */?>
    <div class="cards">
        <h5 class="text-center m-3" style="position: relative;">
            <strong>7th &amp; 8th Floor, Urban Administrative Office Campus, Chennai – 600 028.</strong>

        </h5>

        <table class="table-bordered tndtp_form_table  text-center">
            <thead class="newhead">
                <tr>
                    <th>Sl. No.</th>
                    <th>Name</th>
                    <th>Telephone (Office)</th>
                </tr>
            </thead>
            <tr>
                <td>1.</td>
                <td><span style="font-weight: bold;">Thiru. Kiran Gurrala, IAS.,&nbsp;</span><br />
                    Director of Town Panchayats</td>
                <td>29520050</td>
            </tr>
            <tr>
                <td>2.</td>
                <td>Thiru S.M.Malayaman Thirumudikari,M.Sc.,(Maths),M.Sc.,(Env),B.Ed., &nbsp;<br />
                    Additional Director of Town Panchayats</td>
                <td>29520050 ext 23</td>
            </tr>
            <tr>
                <td>3.</td>
                <td>Thiru.K.Elangovan ,B.Sc.,<br />
                    Joint Director (General)</td>
                <td>29520051 ext 22</td>
            </tr>
            <tr>
                <td>4.</td>
                <td>Tmt. M.Meenakhsi ,M.Com.,<br />
                    Joint Director(Scheme)</td>
                <td> </td>
            </tr>
            <tr>
                <td>5.</td>
                <td>Thiru. G.Ravi,B.E.,M.B.A.,    <br />
                    Superintending Engineer</td>
                <td>29520051 ext 24</td>
            </tr>
            <tr>
                <td>6.</td>
                <td>Thiru.K.Vaithiyalingam,B.E.,<br />
                    Executive Engineer</td>
                <td>29520051 ext 25</td>
            </tr>
            <tr>
                <td>7.</td>
                <td>System Analyst(Vacant)</td>
                <td>29520051 ext 28</td>
            </tr>
            <tr>
                <td>8.</td>
                <td>Tmt. N.Vijayalakshmi<br />
                    Assistant Director of Town Panchayats (Scheme)</td>
                <td>29520051 ext 27</td>
            </tr>
            <tr>
                <td>9.</td>
                <td>Tmt. K.Chitra<br>
                    Assistant Director of Town Panchayats (Establishment) </td>
                <td>29520051 ext 26</td>
            </tr>
            <tr>
                <td>10.</td>
                <td>Selvi.G.Kavitha Ramani<br />
                    Accounts Officer</td>
                <td>29520051 ext 34</td>
            </tr>
            <tr>
                <td>11.</td>
                <td>Thiru. R.Jayaseelan<br />
                    Assistant Executive Engineer</td>
                <td>29520051 ext 38</td>
            </tr>
            <tr>
                <td>12.</td>
                <td>Thiru.S.Gajendiran,M.A.,B.L.,<br />
                    Law Officer</td>
                <td>29520051 ext 47</td>
            </tr>
        </table>
    </div>
    <div class="cards">
        <h4 class="m-3 text-center">EPAPX : 29520051, FAX : 29520053 </h4>
        <table class=" table-bordered tndtp_form_table text-center">
            <tr>
                <td> E-Mail : </td>
                <td>dtpsystem[at]gmail[dot]com<u>,&nbsp;</u>dtp[dot]tn[at]nic[dot]in</td>
            </tr>
        </table>
    </div>
    <div class="cards">
        <h5 class="text-center  m-3">Website Information Manager</h5>
        <table class="table-bordered tndtp_form_table  text-center">
            <tbody>
                <tr>
                    <td width="30%" class="style20">
                        <p>Name</p>
                    </td>
                    <td width="70%" class="style20">
                        <p>Thiru S.M.Malayaman Thirumudikari,M.Sc.,(Maths),M.Sc.,(Env),B.Ed.,</p>
                    </td>
                </tr>
                <tr>
                    <td width="30%" class="style20">
                        <p>Designation</p>
                    </td>
                    <td width="70%" class="style20">
                        <p>Additional Director of Town Panchayats</p>
                    </td>
                </tr>
                <tr>
                    <td width="30%" class="style20">
                        <p>Address</p>
                    </td>
                    <td width="70%" class="style20">
                        <p>Directorate of Town Panchayats,7th &amp; 8th Floor, Urban Administrative <br />
                            Office Campus, Chennai-600028.</p>
                    </td>
                </tr>
                <tr>
                    <td width="30%" class="style20">
                        <p>Phone No</p>
                    </td>
                    <td width="70%" class="style20">
                        <p>044- 29520051</p>
                    </td>
                </tr>
                <tr>
                    <td width="30%" class="style20">
                        <p>Email</p>
                    </td>
                    <td width="70%" class="style20">
                        <p>dtp[at]tn[dot]nic[dot]in, dtpsystem [at]gmail[dot]com</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="cards">
        <h4 class="text-center  m-3"> Zone / District Level Officers </h4>
        <h5 class="text-center  m-3">LIST OF ASSISTANT DIRECTOR OF TOWN PANCHAYATS</h5>
        <table class="table-bordered tndtp_form_table text-center">
            <thead class="newhead">
                <tr>
                    <td>
                        <p><strong>Sl. No.</strong> </p>
                    </td>
                    <td>
                        <p><strong>Zone</strong> </p>
                    </td>
                    <td>
                        <p><strong>Name of the ADTP</strong>&nbsp;(Thiru/Tmt.)</p>
                    </td>
                    <td>
                        <p><strong>Office Phone No.</strong> </p>
                    </td>
                    <td>
                        <p><strong>Cell No.</strong> </p>
                    </td>
                    <td>
                        <p><strong>E-Mail ID</strong> </p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <p align="center">1</p>
                    </td>
                    <td>
                        <p>Kancheepuram</p>
                    </td>
                    <td>
                        <p>N.Latha</p>
                    </td>
                    <td>
                        <p>044-27237710</p>
                    </td>
                    <td>
                        <p>8925809212</p>
                    </td>
                    <td>
                        <p>adtp[dot]tnkpm[at]nic[dot]in</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">2</p>
                    </td>
                    <td>
                        <p>Thiruvallur</p>
                    </td>
                    <td>
                        <p>S.Jayakumar</p>
                    </td>
                    <td>
                        <p>044-27665953</p>
                    </td>
                    <td>
                        <p>8925809213</p>
                    </td>
                    <td>
                        <p>adtptlr[at]tn[dot]nic[dot]in</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">3</p>
                    </td>
                    <td>
                        <p>Vellore</p>
                    </td>
                    <td>
                        <p>R.Gururajan</p>
                    </td>
                    <td>
                        <p>0416-2253647</p>
                    </td>
                    <td>
                        <p>8925809214</p>
                    </td>
                    <td>
                        <p>adtpvlr[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">4</p>
                    </td>
                    <td>
                        <p>Dharmapuri</p>
                    </td>
                    <td>
                        <p>S.Ganesh</p>
                    </td>
                    <td>
                        <p>04342-230849</p>
                    </td>
                    <td>
                        <p>8925809215</p>
                    </td>
                    <td>
                        <p>adtpdpi[at]tn[dot]nic[dot]in</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">5</p>
                    </td>
                    <td>
                        <p>Salem</p>
                    </td>
                    <td>
                        <p>P.Ganesharam</p>
                    </td>
                    <td>
                        <p>0427-2413184</p>
                    </td>
                    <td>
                        <p>8925809216</p>
                    </td>
                    <td>
                        <p>adtpsalemzone[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">6</p>
                    </td>
                    <td>
                        <p>Erode</p>
                    </td>
                    <td>
                        <p>G.Dwaraganathsingh</p>
                    </td>
                    <td>
                        <p>0424-2265492</p>
                    </td>
                    <td>
                        <p>8925809217</p>
                    </td>
                    <td>
                        <p>adtperode[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">7</p>
                    </td>
                    <td>
                        <p>Coimbatore</p>
                    </td>
                    <td>
                        <p>N.Manikandan</p>
                    </td>
                    <td>
                        <p>0422-2301210</p>
                    </td>
                    <td>
                        <p>8925809218</p>
                    </td>
                    <td>
                        <p>adtpcbe1[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">8</p>
                    </td>
                    <td>
                        <p>Udhagamandalam</p>
                    </td>
                    <td>
                        <p>P. Ibrahimsha</p>
                    </td>
                    <td>
                        <p>0423-2442582</p>
                    </td>
                    <td>
                        <p>8925809219</p>
                    </td>
                    <td>
                        <p>adtpnlg[at]nic[dot]in</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">9</p>
                    </td>
                    <td>
                        <p>Cuddalore</p>
                    </td>
                    <td>
                        <p>K.Venkatesan</p>
                    </td>
                    <td>
                        <p>04142-294542</p>
                    </td>
                    <td>
                        <p>8925809220</p>
                    </td>
                    <td>
                        <p>adtp-tncud[at]nic[dot]in</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">10</p>
                    </td>
                    <td>
                        <p>Thanjavur</p>
                    </td>
                    <td>
                        <p>M.Maheen Abubacker</p>
                    </td>
                    <td>
                        <p>04362-234247</p>
                    </td>
                    <td>
                        <p>8925809225</p>
                    </td>
                    <td>
                        <p>adtptjrzone21[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">11</p>
                    </td>
                    <td>
                        <p>Trichy</p>
                    </td>
                    <td>
                        <p>T.Kaliappan</p>
                    </td>
                    <td>
                        <p>0431-465956</p>
                    </td>
                    <td>
                        <p>8925809226</p>
                    </td>
                    <td>
                        <p>aadtptrichy[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">12</p>
                    </td>
                    <td>
                        <p>Dindigul</p>
                    </td>
                    <td>
                        <p>R.Manoranjtham</p>
                    </td>
                    <td>
                        <p>0451-2460090</p>
                    </td>
                    <td>
                        <p>8925809227</p>
                    </td>
                    <td>
                        <p>adtpdgl[at]ymail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">13</p>
                    </td>
                    <td>
                        <p>Madurai</p>
                    </td>
                    <td>
                        <p>S.Sethuraman</p>
                    </td>
                    <td>
                        <p>0452-2530564</p>
                    </td>
                    <td>
                        <p>8925809229</p>
                    </td>
                    <td>
                        <p>adtpmduzone[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">14</p>
                    </td>
                    <td>
                        <p>Theni</p>
                    </td>
                    <td>
                        <p>N.Balasubramani</p>
                    </td>
                    <td>
                        <p>04546-265535</p>
                    </td>
                    <td>
                        <p>8925809228</p>
                    </td>
                    <td>
                        <p>adtpthenizone[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">15</p>
                    </td>
                    <td>
                        <p>Sivagangai</p>
                    </td>
                    <td>
                        <p>R.Raja</p>
                    </td>
                    <td>
                        <p>04575-243046</p>
                    </td>
                    <td>
                        <p>8925809230</p>
                    </td>
                    <td>
                        <p>adtpsvg[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">16</p>
                    </td>
                    <td>
                        <p>Tirunelveli</p>
                    </td>
                    <td>
                        <p>A.William Yesudass</p>
                    </td>
                    <td>
                        <p>0462-2500809</p>
                    </td>
                    <td>
                        <p>8925809231</p>
                    </td>
                    <td>
                        <p>adtptnv1[at]gmail[dot]com</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p align="center">17</p>
                    </td>
                    <td>
                        <p>Nagercoil</p>
                    </td>
                    <td>
                        <p>SP.Sathiamoorthy</p>
                    </td>
                    <td>
                        <p>04652-279400</p>
                    </td>
                    <td>
                        <p>8925809232</p>
                    </td>
                    <td>
                        <p>adtpkkngl[at]gmail[dot]com</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="cards">
        <h5 class="text-center  m-3">LIST OF EXECUTIVE ENGINEERS</h5>
        <table class="table-bordered tndtp_form_table text-center">
            <thead class="newhead">
                <tr>
                    <td width="8%" class="style20">
                        <p>Sl. &nbsp;No.</p>
                    </td>
                    <td width="27%" class="style20">
                        <p>Head Quarter</p>
                    </td>
                    <td width="33%" class="style20">
                        <p>Name of the E.E. (Thiru./Tmt.)</p>
                    </td>
                    <td>
                        <p>Office Ph No.</p>
                    </td>
                    <td>
                        <p>Cell No.</p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="8%" class="style20">
                        <p align="center">1</p>
                    </td>
                    <td width="27%" class="style20">
                        <p>Coimbatore</p>
                    </td>
                    <td width="33%" class="style20">
                        <p>P.Mohan</p>
                    </td>
                    <td>
                        <p>0422-2301210</p>
                    </td>
                    <td>
                        <p>8925809235</p>
                    </td>
                </tr>
                <tr>
                    <td width="8%" class="style20">
                        <p align="center">2</p>
                    </td>
                    <td width="27%" class="style20">
                        <p>Dharmapuri</p>
                    </td>
                    <td width="33%" class="style20">
                        <p>R.Ganesamoorthi</p>
                    </td>
                    <td>
                        <p>04342 230849</p>
                    </td>
                    <td>
                        <p>8925809234</p>
                    </td>
                </tr>
                <tr>
                    <td width="8%" class="style20">
                        <p align="center">3</p>
                    </td>
                    <td width="27%" class="style20">
                        <p>Trichy</p>
                    </td>
                    <td width="33%" class="style20">
                        <p>KR.S.Karuppiah</p>
                    </td>
                    <td>
                        <p>0431-2465956</p>
                    </td>
                    <td>
                        <p>8925809233</p>
                    </td>
                </tr>
                <tr>
                    <td width="8%" class="style20">
                        <p align="center">4</p>
                    </td>
                    <td width="27%" class="style20">
                        <p>Madurai</p>
                    </td>
                    <td width="33%" class="style20">
                        <p>M.Sairaj</p>
                    </td>
                    <td>
                        <p>0452-2530564</p>
                    </td>
                    <td>
                        <p>8925809236</p>
                    </td>
                </tr>
                <tr>
                    <td width="8%" class="style20">
                        <p align="center">5</p>
                    </td>
                    <td width="27%" class="style20">
                        <p>Tirunelveli</p>
                    </td>
                    <td width="33%" class="style20">
                        <p>P.T.Tharmaraj</p>
                    </td>
                    <td>
                        <p>0462-2500809</p>
                    </td>
                    <td>
                        <p>8925809237</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="cards">
        <h5 class="text-center  m-3">LIST OF ASSISTANT EXECUTIVE ENGINEERS</h5>
        <table class="table-bordered tndtp_form_table text-center">
            <thead class="newhead">
                <tr>
                    <td width="7%" class="style20">
                        <p>Sl. No.</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Head Quarter</p>
                    </td>
                    <td>
                        <p>Name of the A.E.E. (Thiru./Tmt.)</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>Office Ph No.</p>
                    </td>
                    <td>
                        <p>Cell No.</p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="style20">
                        <p align="center">1</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Kancheepuram</p>
                    </td>
                    <td>
                        <p>U.Saravanan(I/C)</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>044-27237710</p>
                    </td>
                    <td>
                        <p align="center">8925809238</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">2</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Thiruvallur</p>
                    </td>
                    <td>
                        <p>U.Saravanan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>044-27665953</p>
                    </td>
                    <td>
                        <p align="center">8925809239</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">3</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Vellore</p>
                    </td>
                    <td>
                        <p>C.Amsa</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0416-2253647</p>
                    </td>
                    <td>
                        <p align="center">8925809240</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">4</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Tiruvannamalai</p>
                    </td>
                    <td>
                        <p>B.Sengutuvan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0416-2253647</p>
                    </td>
                    <td>
                        <p align="center">8925809241</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">5</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Dharmapuri &amp; Krishnagiri</p>
                    </td>
                    <td>
                        <p>R.Ganesan(I/c)</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04342 230849</p>
                    </td>
                    <td>
                        <p align="center">8925809242</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">6</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Salem &nbsp;I</p>
                    </td>
                    <td>
                        <p>R.Ganesan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0427-2413184</p>
                    </td>
                    <td>
                        <p align="center">8925809243</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">7</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Salem&nbsp; II</p>
                    </td>
                    <td>
                        <p>R.Kumar</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0427-2413184</p>
                    </td>
                    <td>
                        <p align="center">8925809244</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">8</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Namakkal</p>
                    </td>
                    <td>
                        <p>K.Palani</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0427-2413184</p>
                    </td>
                    <td>
                        <p align="center">8925809245</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">9</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Erode &nbsp;I</p>
                    </td>
                    <td>
                        <p>S.Varatharajan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0424-2265492</p>
                    </td>
                    <td>
                        <p align="center">8925809246</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">10</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Erode&nbsp; II</p>
                    </td>
                    <td>
                        <p>M.Ganesan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0424-2265492</p>
                    </td>
                    <td>
                        <p align="center">8925809247</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">11</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Tiruppur</p>
                    </td>
                    <td>
                        <p>T.R.Srinivasamoorthi</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0424-2265492</p>
                    </td>
                    <td>
                        <p align="center">8925809248</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">12</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>&nbsp;Coimbatore I&nbsp;&nbsp;</p>
                    </td>
                    <td>
                        <p>M.Lalithamani</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0422-2301210</p>
                    </td>
                    <td>
                        <p align="center">8925809249</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">13</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>&nbsp;Coimbatore II&nbsp;&nbsp;</p>
                    </td>
                    <td>
                        <p>N.Raja</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0422-2301210</p>
                    </td>
                    <td>
                        <p align="center">8925809250</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">14</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Udhagamandalam</p>
                    </td>
                    <td>
                        <p>N.Raja (I/c)</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0423-2442582</p>
                    </td>
                    <td>
                        <p align="center">8925809251</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">15</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Cuddalore</p>
                    </td>
                    <td>
                        <p>S.Shanmugam</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04142- 214542</p>
                    </td>
                    <td>
                        <p align="center">8925809252</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">16</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Villupuram&Kallakurichi</p>
                    </td>
                    <td>
                        <p>P.Radhakrishnan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04142- 214542</p>
                    </td>
                    <td>
                        <p align="center">8925809253</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">17</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Thanjavur</p>
                    </td>
                    <td>
                        <p>J.Mathavan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04362-234247</p>
                    </td>
                    <td>
                        <p align="center">8925809254</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">18</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Nagapattinam , Thiruvarur &amp; Mayiladuthurai</p>
                    </td>
                    <td>
                        <p>G.Thiyagarajan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04362-234247</p>
                    </td>
                    <td>
                        <p align="center">8925809255</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">19</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Trichy</p>
                    </td>
                    <td>
                        <p>R.Radha</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0431-2465956</p>
                    </td>
                    <td>
                        <p align="center">8925809256</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">20</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Perambalur, Ariyalur &amp; Puthukottai</p>
                    </td>
                    <td>
                        <p>D.Dhamayanthi</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0431-2465956</p>
                    </td>
                    <td>
                        <p align="center">8925809257</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">21</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Dindigul</p>
                    </td>
                    <td>
                        <p>V.Vetriselvi</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0451-2460090</p>
                    </td>
                    <td>
                        <p align="center">8925809258</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">22</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Karur</p>
                    </td>
                    <td>
                        <p>T.M.Menaga</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0451-2460090</p>
                    </td>
                    <td>
                        <p align="center">8925809259</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">23</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Madurai &nbsp;&amp; Viruthunagar</p>
                    </td>
                    <td>
                        <p>K.Manikandan Avudaiyappan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0452-2530564</p>
                    </td>
                    <td>
                        <p align="center">8925809261</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">24</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Theni</p>
                    </td>
                    <td>
                        <p>R.Manimaran</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04546-265535</p>
                    </td>
                    <td>
                        <p align="center">8925809260</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">25</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Sivaganga &amp; Ramanathapuram</p>
                    </td>
                    <td>
                        <p>M.Jeyakrishnan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04575-243046</p>
                    </td>
                    <td>
                        <p align="center">8925809262</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">26</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Tirunelveli</p>
                    </td>
                    <td>
                        <p>V.Siva Sankaralingam</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0462-2500809</p>
                    </td>
                    <td>
                        <p align="center">8925809263</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">27</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Tenkasi</p>
                    </td>
                    <td>
                        <p>S.Thiruselvam</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0462-2500809</p>
                    </td>
                    <td>
                        <p align="center">8925809264</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">28</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Thoothukudi</p>
                    </td>
                    <td>
                        <p>K.Hariharan</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>0462-2500809</p>
                    </td>
                    <td>
                        <p align="center">8925809265</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">29</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Nagercoil &nbsp;I</p>
                    </td>
                    <td>
                        <p>S.Pushpalatha</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04652-279400</p>
                    </td>
                    <td>
                        <p align="center">8925809266</p>
                    </td>
                </tr>
                <tr>
                    <td class="style20">
                        <p align="center">30</p>
                    </td>
                    <td width="29%" class="style20">
                        <p>Nagercoil &nbsp;II</p>
                    </td>
                    <td>
                        <p>S.Marimuthu</p>
                    </td>
                    <td width="17%" class="style20">
                        <p>04652-279400</p>
                    </td>
                    <td>
                        <p align="center">8925809267</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
</div>
</div>

<?php

        // #############

        // PAGE CONTENT END

        // #############

        $ob_output_main_contents = ob_get_contents();
        ob_clean();
        $this->Template("PublicTemplate", "Disclaimer", $ob_output_main_contents,array(),array('page_id'=>12));
		
		
    }
	public function data_save($save_data){
   
		if (!$this->validateToken($this->page_token, $save_data[$this->page_token])) {
			$this->main_content(array_merge(array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => $this->page_token,
				"MESSAGE" => "Invalid Token"
			), $save_data));
      		exit;
		}
		if(isset($save_data['dcode']) && $save_data['dcode']!='')
		{
			$dcode = $save_data['dcode'];	
			$dcode_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=> $dcode,
			'Field_Name'=>'dcode',
			'Field_Label_Name'=>'District',
			"Field_Min_length" => 1,
			"Field_Max_length" => 2
			)
			);			  
			if ($dcode_Validation['Status'] == "Error") {
				 $this->main_content(array(
					  "STATUS" => "FAIL",
					  "STATUS_TYPE" => "FORM",
					  "MESSAGE" => "Invalid District"
					  ));
					  exit;	
			}
		}else{
			 $this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid District"
			));
			exit;		
		}
		if(isset($save_data['lbcode']) && $save_data['lbcode']!='')
		{
			$lbcode = $save_data['lbcode'];	
			$lbcode_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=> $lbcode,
			'Field_Name'=>'lbcode',
			'Field_Label_Name'=>'Town Panchayat',
			'Field_length' =>6
			)
			);			  
			if ($lbcode_Validation['Status'] == "Error") {
				 $this->main_content(array(
					  "STATUS" => "FAIL",
					  "STATUS_TYPE" => "FORM",
					  "MESSAGE" => "Invalid Town Panchayat"
					  ));
					  exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Town Panchayat"
			));
			exit;		
		}
		if(isset($save_data['taxid']) && $save_data['taxid']!='')
		{
			$taxid = $save_data['taxid'];	
			$taxid_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=> $taxid,
			'Field_Name'=>'taxid',
			'Field_Label_Name'=>'Tax Type',
			'Field_length' =>1
			)
			);			  
			if ($taxid_Validation['Status'] == "Error") {
			   $this->main_content(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Invalid Tax Type"
				));
				exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Tax Type"
			));
			exit;		
		}
		if(isset($save_data['txtname']) && $save_data['txtname']!='')
		{
			$name = $save_data['txtname'];	
			$txtname_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=> $name,
			'Field_Name'=>'txtname',
			'Field_Label_Name'=>'Name',
			"Field_Min_length" => 1,
			"Field_Max_length" => 30
			)
			);			  
			if ($taxid_Validation['Status'] == "Error") {
			   $this->main_content(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Invalid Name"
				));
				exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Name"
			));
			exit;		
		}
		
			
      if(isset($save_data['txtemail']) && $save_data['txtemail']!='')
      {
        $email = $save_data['txtemail'];	
        $email_Validation=$this->Field_Validation(
        array
        (
        'Field_Type'=>'email',
        'Field_Value'=> $email,
        'Field_Name'=>'txtemail',
        'Field_Label_Name'=>'Email'
        )
        );			  
        if ($email_Validation['Status'] == "Error") {
           $this->main_content(array(
          "STATUS" => "FAIL",
          "STATUS_TYPE" => "FORM",
          "MESSAGE" => "Invalid Email Number"
          ));
          exit;	
        }
      }else{
        $this->main_content(array(
        "STATUS" => "FAIL",
        "STATUS_TYPE" => "FORM",
        "MESSAGE" => "Invalid Email Number"
        ));
        exit;		
      }
			
		if(isset($save_data['txtmbl']) && $save_data['txtmbl']!='')
		{
			$mbl = $save_data['txtmbl'];	
			$txtmbl_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'number',
			'Field_Value'=> $mbl,
			'Field_Name'=>'txtmbl',
			'Field_Label_Name'=>'Mobile Number',
			'Field_length'=>10
			)
			);			  
			if ($txtmbl_Validation['Status'] == "Error") {
			   $this->main_content(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Invalid Mobile Number"
				));
				exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Mobile Number"
			));
			exit;		
		}
		if(isset($save_data['txtsubject']) && $save_data['txtsubject']!='')
		{
			$subject = $save_data['txtsubject'];	
			$txtsubject_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=> $subject,
			'Field_Name'=>'Subject',
			'Field_Label_Name'=>'Subject',
			"Field_Min_length" => 1,
			"Field_Max_length" => 100
			)
			);			  
			if ($txtsubject_Validation['Status'] == "Error") {
			   $this->main_content(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Invalid Subject"
				));
				exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Subject"
			));
			exit;		
		}
		if(isset($save_data['txtdescription']) && $save_data['txtdescription']!='')
		{
			$message_content = $save_data['txtdescription'];	
			$txtdescription_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=> $message_content,
			'Field_Name'=>'Message',
			'Field_Label_Name'=>'Message',
			"Field_Min_length" => 1,
			"Field_Max_length" => 100
			)
			);			  
			if ($txtdescription_Validation['Status'] == "Error") {
			   $this->main_content(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Invalid Message"
				));
				exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Message"
			));
			exit;		
		}
		if(isset($save_data['txtCaptcha']) && $save_data['txtCaptcha']!='')
		{
			$captcha = $save_data['txtCaptcha'];	
			$captcha_Validation=$this->Field_Validation(
			array
			(
			'Field_Type'=>'text',
			'Field_Value'=> $captcha,
			'Field_Name'=>'Captcha',
			'Field_Label_Name'=>'Captcha',
			'Field_length'=>6
			)
			);			  
			if ($captcha_Validation['Status'] == "Error") {
			   $this->main_content(array(
				"STATUS" => "FAIL",
				"STATUS_TYPE" => "FORM",
				"MESSAGE" => "Invalid Captcha"
				));
				exit;	
			}
		}else{
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Captcha"
			));
			exit;		
		}
		if($captcha != $_SESSION['login_captcha']){
			$this->main_content(array(
			"STATUS" => "FAIL",
			"STATUS_TYPE" => "FORM",
			"MESSAGE" => "Invalid Captcha"
			));
			exit;
		}
		$address='';
		$getCurrentUser = $this->getCurrentUser();
		$getIpAddress = $this->getIpAddress();$get_email="SELECT eo_mail_id_personal FROM master.m_eo_details where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive;";
		$ger_email_res=$this->prepare($get_email,array(":dcode"=>$dcode, ":lbcode" =>$lbcode, ":isactive"=>1),4);
		if(isset($ger_email_res['eo_mail_id_personal']) && $ger_email_res['eo_mail_id_personal']!=''){
			$get_district="select district_name_en,lbody_name_en from
			(SELECT dcode,district_name_en FROM master.m_district where dcode=:dcode)a
			left join
			(SELECT dcode,lbcode,lbody_name_en FROM master.m_localbodies where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive)b
			on a.dcode=b.dcode ;";
			$get_district_res=$this->prepare($get_district,array(":dcode"=>$dcode, ":lbcode" =>$lbcode, ":isactive"=>1),4);
			$get_taxtype="select  taxtypeid,taxtypedesc_en from master.m_taxtype where taxtypeid=:taxtypeid ;";
			$get_taxtype_res=$this->prepare($get_taxtype,array(":taxtypeid"=>$taxid),4);
			$message = '
<html>
<head>
<style>
  body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
  }
  .container {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
  }
  .header {
    background-color: #00446d;
    color: #fff;
    padding: 10px 0;
    text-align: center;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }
  .content {
    padding: 20px 0;
  }
  .content b {
    display: inline-block;
    width: 200px;
    font-weight: bold;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>FeedBack From Public</h2>
    </div>
    <div class="content">
      <b>District :</b> ' . $get_district_res['district_name_en'] . '<br/><br/>
      <b>Town Panchayat :</b> ' . $get_district_res['lbody_name_en'] . '<br/><br/>
      <b>Tax :</b> ' . $get_taxtype_res['taxtypedesc_en'] . '<br/><br/>
      <b>Public Name :</b> ' . $name . '<br/><br/>
      <b>Public Email :</b> ' . $email . '<br/><br/>
      <b>Public Mobile Number :</b> ' . $mbl .'<br/><br/>
      <b>Subject :</b> ' . $subject .'<br/><br/>
      <b>Message From Public :</b> ' . $message_content .'
    </div>
  </div>
</body>
</html>';
		}
		$save_query="select * from security.sp_feedback(:name, :email, :mblno, :address, :tax, :subject, :feed_back, :dcode, :lbcode, :ip_address, :username, 0, 0);";
		$res=$this->prepare($save_query,array(":name"=>$name,":email"=>$email,":mblno"=>$mbl,":address"=>$address,":tax"=>$taxid,":subject"=>$subject,":feed_back"=>$message_content,":dcode"=>$dcode, ":lbcode"=>$lbcode, ":ip_address"=>$getIpAddress, ":username"=>$getCurrentUser),4);
 
		if($this->prepareStatus($res)==true){
			$this->commit();
			$get_email="SELECT eo_mail_id_personal FROM master.m_eo_details where dcode=:dcode and lbcode=:lbcode and del_flag is null and isactive=:isactive;";
			$ger_email_res=$this->prepare($get_email,array(":dcode"=>$dcode, ":lbcode" =>$lbcode, ":isactive"=>1),4);
			$to_mail_id=$ger_email_res['eo_mail_id_personal'];
			$email_result = $this->sendEmail(1,$to_mail_id,$subject,$message,$message_content,array(),array());
			$this->main_content(array(
			"STATUS" => "SUCCESS",
			"MESSAGE" => "Thank you for your feedback. We will get back to you soon."
			));
			exit;
		}else{
			$this->rollBack();
			$this->main_content(array(
			"STATUS" => "FAIL",
			"MESSAGE" => "Data Save Failed "
			));
			exit;
		}
	}
}
$Home = new ContactUs();
if(isset($_POST['btnsubmit'])){
	$Home->data_save($_POST);
}else if (isset($_POST['cmd']) && $_POST['cmd']!=''){
	$cmd=base64_decode($_POST['cmd']);
	$cmd_Validation = $Home->Field_Validation(array(
		"Field_Type" => "number",
		"Field_Value" => $cmd,
		"Field_Label_Name" => "Key",
		"Field_Name" => "Key",
		"Field_length" => 1
	));
	if ($cmd_Validation['Status'] == "Error") {
		$result_data = array(
			"STATUS" => "ERROR",
			"STATUS_TYPE" => "FIELD",
			"FIELD_NAME" => "cmd",
			"MESSAGE" => "Invalid Key"
		);
		$Home->main_content($result_data);
		exit();
	}
	if($cmd == 1){
		if(isset($_POST['dcode']) && $_POST['dcode']!=''){
			$dcode=base64_decode($_POST['dcode']);
			$dcode_Validation = $Home->Field_Validation(array(
				"Field_Type" => "number",
				"Field_Value" => $dcode,
				"Field_Label_Name" => "dcode",
				"Field_Name" => "District",
				"Field_Min_length" => 1,
				"Field_Max_length" => 2
			));
			if ($dcode_Validation['Status'] == "Error") {
				$result_data = array(
					"STATUS" => "ERROR",
					"STATUS_TYPE" => "FIELD",
					"FIELD_NAME" => "dcode",
					"MESSAGE" => "Invalid District"
				);
				$Home->main_content($result_data);
				exit();
			}
		}else{
			$result_data = array(
				"STATUS" => "ERROR",
				"STATUS_TYPE" => "FIELD",
				"FIELD_NAME" => "dcode",
				"MESSAGE" => "Select District"
			);
			$Home->main_content($result_data);
			exit();
		}
		$sel_qry="select lbcode, lbody_name_en from master.m_localbodies where dcode=:dcode and del_flag is null and isactive=:isactive;";
		$sel_qry_res=$Home->prepare($sel_qry, array(':dcode'=>$dcode, ':isactive'=>1),2);
		?>
<option value="">Town Panchayat</option>
<?php
		foreach($sel_qry_res as $sel_qry_row){
			?>
<option value="<?php echo htmlentities($sel_qry_row['lbcode']); ?>">
    <?php echo htmlentities($sel_qry_row['lbody_name_en']); ?></option>
<?php
		}
		exit;
	}
}else{
	$Home->main_content();
}
?>
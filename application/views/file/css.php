<?php
header("Content-type: text/css");
?>
div.alert{
	top: 50px;
	/* z-index: 99999; */
	position: absolute;
	width: 100%;
}
{page/home$visited;>50}
#footer p a:hover{
	color:red;
	font-size:25px;
}
{/$visited}
html { 
{#date;Hi;>1759%1}
{#date;Hi;<2159}
  background: url(../images/bg_evening_miriadna_com.jpg) no-repeat center center fixed; 
{/#date}
{/#date%1}

{#date;Hi;>2200%2}
  background: url(../images/bg_night_wallpapertoon_com.jpg) no-repeat center center fixed; 
{/#date%2}

{#date;Hi;>0%1}
{#date;Hi;<700}
  background: url(../images/bg_night_wallpapertoon_com.jpg) no-repeat center center fixed; 
{/#date}
{/#date%1}


{#date;Hi;>1159%1}
{#date;Hi;<1800}
  background: url(../images/bg_midday_fc09_devianart_net.jpg) no-repeat center center fixed; 
{/#date}
{/#date%1}

{#date;Hi;>659%3}
{#date;Hi;<1200}
  background: url(../images/bg_morning_personal_psu_edu.jpg) no-repeat center center fixed; 
{/#date}
{/#date%3}

  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
body{
	/*background-color: rgba(255,255,255,.7);*/
	background-color: transparent;

}
#main-container{
	border-radius:5px;
	background-color: rgba(245, 245, 245, 0.8);
}
#footer p a{
	background-color: transparent;;
	color: white;
}
.navbar-custom {
  background-color: #e3e3e3;
  border-color: #d2d2d2;
  background-image: -webkit-gradient(linear, left 0%, left 100%, from(#fcfcfc), to(#e3e3e3));
  background-image: -webkit-linear-gradient(top, #fcfcfc, 0%, #e3e3e3, 100%);
  background-image: -moz-linear-gradient(top, #fcfcfc 0%, #e3e3e3 100%);
  background-image: linear-gradient(to bottom, #fcfcfc 0%, #e3e3e3 100%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffcfcfc', endColorstr='#ffe3e3e3', GradientType=0);
}
.navbar-custom .navbar-brand {
  color: #3b3b3b;
}
.navbar-custom .navbar-brand:hover,
.navbar-custom .navbar-brand:focus {
  color: #222222;
  background-color: transparent;
}
.navbar-custom .navbar-text {
  color: #3b3b3b;
}
.navbar-custom .navbar-nav > li:last-child > a {
  border-right: 1px solid #d2d2d2;
}
.navbar-custom .navbar-nav > li > a {
  color: #3b3b3b;
  border-left: 1px solid #d2d2d2;
}
.navbar-custom .navbar-nav > li > a:hover,
.navbar-custom .navbar-nav > li > a:focus {
  color: #000000;
  background-color: transparent;
}
.navbar-custom .navbar-nav > .active > a,
.navbar-custom .navbar-nav > .active > a:hover,
.navbar-custom .navbar-nav > .active > a:focus {
  color: #000000;
  background-color: #d2d2d2;
  background-image: -webkit-gradient(linear, left 0%, left 100%, from(#d2d2d2), to(#ececec));
  background-image: -webkit-linear-gradient(top, #d2d2d2, 0%, #ececec, 100%);
  background-image: -moz-linear-gradient(top, #d2d2d2 0%, #ececec 100%);
  background-image: linear-gradient(to bottom, #d2d2d2 0%, #ececec 100%);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd2d2d2', endColorstr='#ffececec', GradientType=0);
}
.navbar-custom .navbar-nav > .disabled > a,
.navbar-custom .navbar-nav > .disabled > a:hover,
.navbar-custom .navbar-nav > .disabled > a:focus {
  color: #cccccc;
  background-color: transparent;
}
.navbar-custom .navbar-toggle {
  border-color: #dddddd;
}
.navbar-custom .navbar-toggle:hover,
.navbar-custom .navbar-toggle:focus {
  background-color: #dddddd;
}
.navbar-custom .navbar-toggle .icon-bar {
  background-color: #cccccc;
}
.navbar-custom .navbar-collapse,
.navbar-custom .navbar-form {
  border-color: #d1d1d1;
}
.navbar-custom .navbar-nav > .dropdown > a:hover .caret,
.navbar-custom .navbar-nav > .dropdown > a:focus .caret {
  border-top-color: #000000;
  border-bottom-color: #000000;
}
.navbar-custom .navbar-nav > .open > a,
.navbar-custom .navbar-nav > .open > a:hover,
.navbar-custom .navbar-nav > .open > a:focus {
  background-color: #d2d2d2;
  color: #000000;
}
.navbar-custom .navbar-nav > .open > a .caret,
.navbar-custom .navbar-nav > .open > a:hover .caret,
.navbar-custom .navbar-nav > .open > a:focus .caret {
  border-top-color: #000000;
  border-bottom-color: #000000;
}
.navbar-custom .navbar-nav > .dropdown > a .caret {
  border-top-color: #3b3b3b;
  border-bottom-color: #3b3b3b;
}
@media (max-width: 767) {
  .navbar-custom .navbar-nav .open .dropdown-menu > li > a {
    color: #3b3b3b;
  }
  .navbar-custom .navbar-nav .open .dropdown-menu > li > a:hover,
  .navbar-custom .navbar-nav .open .dropdown-menu > li > a:focus {
    color: #000000;
    background-color: transparent;
  }
  .navbar-custom .navbar-nav .open .dropdown-menu > .active > a,
  .navbar-custom .navbar-nav .open .dropdown-menu > .active > a:hover,
  .navbar-custom .navbar-nav .open .dropdown-menu > .active > a:focus {
    color: #000000;
    background-color: #d2d2d2;
  }
  .navbar-custom .navbar-nav .open .dropdown-menu > .disabled > a,
  .navbar-custom .navbar-nav .open .dropdown-menu > .disabled > a:hover,
  .navbar-custom .navbar-nav .open .dropdown-menu > .disabled > a:focus {
    color: #cccccc;
    background-color: transparent;
  }
}
.navbar-custom .navbar-link {
  color: #3b3b3b;
}
.navbar-custom .navbar-link:hover {
  color: #000000;
}
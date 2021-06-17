<!DOCTYPE >
<html>
<head>
<meta charset="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1">
<title>401 - Unauthorized</title>
<style type="text/css">
body {
  font-family: 'Raleway', sans-serif !important;
}
.header {
  background: #f3d2d5; /* Old browsers */
  background: -moz-linear-gradient(left, #f3d2d5, #e5c8c3); /* FF3.6-15 */
  background: -webkit-linear-gradient(left, #f3d2d5, #e5c8c3); /* Chrome10-25,Safari5.1-6 */
  background: linear-gradient(to right, #f3d2d5, #e5c8c3); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f3d2d5', endColorstr='#e5c8c3', GradientType=1 ); /* IE6-9 */
  -webkit-box-shadow: 0px 3px 4px 0px rgba(209, 207, 207, 1);
  -moz-box-shadow: 0px 3px 4px 0px rgba(209, 207, 207, 1);
  box-shadow: 0px 3px 4px 0px rgba(209, 207, 207, 1);
  padding:5px 10px;
}
.error_container {
  position: fixed;
  top: 50%;
  transform: translate(0, -50%);
  margin: 0 auto;
  -webkit-transform: translate(0, -50%);
  width: 100%;
  text-align: center;
}
.error_container .error {
  background: #fff;
  box-shadow: 0 0 12px #999999;
  max-width: 300px;
  text-align: center;
  padding: 30px;
  position: relative;
  margin: 0 auto 20px;
}
h1 {
  text-transform: capitalize;
  font-size: 30px;
  font-family: 'Raleway', sans-serif !important;
}
a.button {
  background-color: #fff;
  border: 1px solid #3a3a3c;
  color: #000 !important;
  padding: 4px 15px;
  font-weight: 600;
  font-size: 13px;
  text-transform: capitalize;
  line-height: 1.5;
 border-radius: .25rem;
  text-decoration: none;
}
</style>
</head>
<body style="background-color: #f7f1f3; padding: 0; margin: 0">
<div class="header "> <a href="{{ route('home') }}" ><img src="{{asset('images/emalogo-black-new.png')}}"/></a> </div>
<div class="error_container">
  <div class="error text-center p-5 card"> <img src="{{asset('images/404_img.png')}}" />
    <h1>401 - Unauthorized</h1>
  </div>
  <a class="button" href="{{ route('home') }}">back to home</a> </div>
</body>
</html>
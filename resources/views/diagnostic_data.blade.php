@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <h1 class="my-2">Diagnostic Data</h1>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-xl-12 col-lg-12 px-0">
      <nav aria-label="breadcrumb">
        {{ Breadcrumbs::render('diagnostic_data') }}
      </nav>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row mt-1">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
    <section class="gridMainContainer mt-0">
      <div class="row">
        <div class="col-xl-7 col-lg-7">
          <h2 class="my-2 pl-3 text-capitalize">Treatment Centre & Hydracool SRP Unit</h2>
        </div>
      </div>
      <div class="col-xl-12 col-lg-12 px-0">
        <div class="table-responsive-lg" id="diagnosticDataScroll">
          <table id="example" class="table table-striped table-bordered example" style="width:100%">
            <thead>
              <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011/04/25</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011/07/25</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Ashton Cox</td>
                <td>Junior Technical Author</td>
                <td>San Francisco</td>
                <td>66</td>
                <td>2009/01/12</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Cedric Kelly</td>
                <td>Senior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>22</td>
                <td>2012/03/29</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Airi Satou</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>33</td>
                <td>2008/11/28</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Brielle Williamson</td>
                <td>Integration Specialist</td>
                <td>New York</td>
                <td>61</td>
                <td>2012/12/02</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Herrod Chandler</td>
                <td>Sales Assistant</td>
                <td>San Francisco</td>
                <td>59</td>
                <td>2012/08/06</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Rhona Davidson</td>
                <td>Integration Specialist</td>
                <td>Tokyo</td>
                <td>55</td>
                <td>2010/10/14</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Colleen Hurst</td>
                <td>Javascript Developer</td>
                <td>San Francisco</td>
                <td>39</td>
                <td>2009/09/15</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Sonya Frost</td>
                <td>Software Engineer</td>
                <td>Edinburgh</td>
                <td>23</td>
                <td>2008/12/13</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Jena Gaines</td>
                <td>Office Manager</td>
                <td>London</td>
                <td>30</td>
                <td>2008/12/19</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Quinn Flynn</td>
                <td>Support Lead</td>
                <td>Edinburgh</td>
                <td>22</td>
                <td>2013/03/03</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Charde Marshall</td>
                <td>Regional Director</td>
                <td>San Francisco</td>
                <td>36</td>
                <td>2008/10/16</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Haley Kennedy</td>
                <td>Senior Marketing Designer</td>
                <td>London</td>
                <td>43</td>
                <td>2012/12/18</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Tatyana Fitzpatrick</td>
                <td>Regional Director</td>
                <td>London</td>
                <td>19</td>
                <td>2010/03/17</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Michael Silva</td>
                <td>Marketing Designer</td>
                <td>London</td>
                <td>66</td>
                <td>2012/11/27</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Paul Byrd</td>
                <td>Chief Financial Officer (CFO)</td>
                <td>New York</td>
                <td>64</td>
                <td>2010/06/09</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Gloria Little</td>
                <td>Systems Administrator</td>
                <td>New York</td>
                <td>59</td>
                <td>2009/04/10</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Bradley Greer</td>
                <td>Software Engineer</td>
                <td>London</td>
                <td>41</td>
                <td>2012/10/13</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Dai Rios</td>
                <td>Personnel Lead</td>
                <td>Edinburgh</td>
                <td>35</td>
                <td>2012/09/26</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Jenette Caldwell</td>
                <td>Development Lead</td>
                <td>New York</td>
                <td>30</td>
                <td>2011/09/03</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Yuri Berry</td>
                <td>Chief Marketing Officer (CMO)</td>
                <td>New York</td>
                <td>40</td>
                <td>2009/06/25</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Caesar Vance</td>
                <td>Pre-Sales Support</td>
                <td>New York</td>
                <td>21</td>
                <td>2011/12/12</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Doris Wilder</td>
                <td>Sales Assistant</td>
                <td>Sydney</td>
                <td>23</td>
                <td>2010/09/20</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Angelica Ramos</td>
                <td>Chief Executive Officer (CEO)</td>
                <td>London</td>
                <td>47</td>
                <td>2009/10/09</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Gavin Joyce</td>
                <td>Developer</td>
                <td>Edinburgh</td>
                <td>42</td>
                <td>2010/12/22</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Jennifer Chang</td>
                <td>Regional Director</td>
                <td>Singapore</td>
                <td>28</td>
                <td>2010/11/14</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Brenden Wagner</td>
                <td>Software Engineer</td>
                <td>San Francisco</td>
                <td>28</td>
                <td>2011/06/07</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Fiona Green</td>
                <td>Chief Operating Officer (COO)</td>
                <td>San Francisco</td>
                <td>48</td>
                <td>2010/03/11</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Shou Itou</td>
                <td>Regional Marketing</td>
                <td>Tokyo</td>
                <td>20</td>
                <td>2011/08/14</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Michelle House</td>
                <td>Integration Specialist</td>
                <td>Sydney</td>
                <td>37</td>
                <td>2011/06/02</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Suki Burks</td>
                <td>Developer</td>
                <td>London</td>
                <td>53</td>
                <td>2009/10/22</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Prescott Bartlett</td>
                <td>Technical Author</td>
                <td>London</td>
                <td>27</td>
                <td>2011/05/07</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Gavin Cortez</td>
                <td>Team Leader</td>
                <td>San Francisco</td>
                <td>22</td>
                <td>2008/10/26</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Martena Mccray</td>
                <td>Post-Sales support</td>
                <td>Edinburgh</td>
                <td>46</td>
                <td>2011/03/09</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Unity Butler</td>
                <td>Marketing Designer</td>
                <td>San Francisco</td>
                <td>47</td>
                <td>2009/12/09</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Howard Hatfield</td>
                <td>Office Manager</td>
                <td>San Francisco</td>
                <td>51</td>
                <td>2008/12/16</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Hope Fuentes</td>
                <td>Secretary</td>
                <td>San Francisco</td>
                <td>41</td>
                <td>2010/02/12</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Vivian Harrell</td>
                <td>Financial Controller</td>
                <td>San Francisco</td>
                <td>62</td>
                <td>2009/02/14</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Timothy Mooney</td>
                <td>Office Manager</td>
                <td>London</td>
                <td>37</td>
                <td>2008/12/11</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Jackson Bradshaw</td>
                <td>Director</td>
                <td>New York</td>
                <td>65</td>
                <td>2008/09/26</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Olivia Liang</td>
                <td>Support Engineer</td>
                <td>Singapore</td>
                <td>64</td>
                <td>2011/02/03</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Bruno Nash</td>
                <td>Software Engineer</td>
                <td>London</td>
                <td>38</td>
                <td>2011/05/03</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Sakura Yamamoto</td>
                <td>Support Engineer</td>
                <td>Tokyo</td>
                <td>37</td>
                <td>2009/08/19</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Thor Walton</td>
                <td>Developer</td>
                <td>New York</td>
                <td>61</td>
                <td>2013/08/11</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Finn Camacho</td>
                <td>Support Engineer</td>
                <td>San Francisco</td>
                <td>47</td>
                <td>2009/07/07</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Serge Baldwin</td>
                <td>Data Coordinator</td>
                <td>Singapore</td>
                <td>64</td>
                <td>2012/04/09</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Zenaida Frank</td>
                <td>Software Engineer</td>
                <td>New York</td>
                <td>63</td>
                <td>2010/01/04</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Zorita Serrano</td>
                <td>Software Engineer</td>
                <td>San Francisco</td>
                <td>56</td>
                <td>2012/06/01</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Jennifer Acosta</td>
                <td>Junior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>43</td>
                <td>2013/02/01</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Cara Stevens</td>
                <td>Sales Assistant</td>
                <td>New York</td>
                <td>46</td>
                <td>2011/12/06</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Hermione Butler</td>
                <td>Regional Director</td>
                <td>London</td>
                <td>47</td>
                <td>2011/03/21</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Lael Greer</td>
                <td>Systems Administrator</td>
                <td>London</td>
                <td>21</td>
                <td>2009/02/27</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Jonas Alexander</td>
                <td>Developer</td>
                <td>San Francisco</td>
                <td>30</td>
                <td>2010/07/14</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Shad Decker</td>
                <td>Regional Director</td>
                <td>Edinburgh</td>
                <td>51</td>
                <td>2008/11/13</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Michael Bruce</td>
                <td>Javascript Developer</td>
                <td>Singapore</td>
                <td>29</td>
                <td>2011/06/27</td>
               <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
              <tr>
                <td>Donna Snider</td>
                <td>Customer Support</td>
                <td>New York</td>
                <td>27</td>
                <td>2011/01/25</td>
                <td><a href="#" data-toggle="modal" data-target="#diagnostic_information"><i class="far fa-eye"></i></a> </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>
  </div>
</div>


<!--Diagnostic Information -->

<div class="modal fade " id="diagnostic_information" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog homeModel" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <h2 class="modal-title w-100">Diagnostic Information</h2>
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
    </div>
      <div class="modal-body px-xl-3 px-lg-3 px-md-3 px-sm-3 max_height">
        <div class="row justify-content-xl-center justify-content-lg-center justify-content-md-center">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <form>
              <div class="form-group my-3">
                <!-- <label for="exampleInputEmail1" class="mb-1">Users Name</label> -->
                <textarea class="form-control" rows="5" id="comment"></textarea>
              </div>
            </form>
          </div>
        </div>
        <div class="row text-center mt-4 mb-2">
          <div class="col-xl-12 col-lg-12 col-md-12">
            <button class="btn btn-secondary text-capitalize" data-toggle="modal" data-target="#SuccessfullMessage" id="popUpClose">submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!--Diagnostic Information -->


<!--Successfully Message -->
<div class="modal fade" id="SuccessfullMessage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog model-md" role="document">
    <div class="modal-content">
      <button type="button" class="close text-right" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">X</span> </button>
      <div class="modal-body text-center px-5">
        <p class="my-5"><i class="far fa-check-circle mt-5" ></i></p>
        <h2 class="mt-3 mb-5">You have created the successfully.</h2>
      </div>
    </div>
  </div>
</div>
<!-- Successfully Message -->
@endsection

@section('jsdependencies')

<script type="text/javascript">
  $(document).ready(function(){
  $("#popUpClose").click(function(){
    $("#diagnostic_information, .modal-backdrop.show").hide();
  });
});
</script>
@endsection
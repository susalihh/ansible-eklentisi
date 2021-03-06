<h1>{{ __('Ansible') }}</h1>
{{ __('Hostname') }}: <b><span id="hostname"></span><br/>
<br>
    <input type="text" name="playbookName" id="playbookname_field" class="container-sm"><small>.yml</small>
    <br><small>Çalıştırmak istediğiniz ansible-playbook adını giriniz</small>

    <div class="mb-1"></div>
    
    <input type="password" name="sudoPassword" id="sudopass_field" class="container-sm">
    <br><small>Sudo şifresini giriniz</small>
    <br><br>

<button class="btn btn-success mb-2" onclick="showModal()" type="button">Çalıştır</button>

@component("modal-component", [
    "id" => "getClientWindow",
    "title" => "",
    "notSized" => true,
    "modalDialogClasses" => "exClass",
    "footer" => [
        "class" => "btn-danger",
        "onclick" => "modalCloseClick()",
        "text" => "Close"
    ]
])    
    <div id="outTable"></div>
@endcomponent

<script>
    getHostname();
    function getHostname(){
        showSwal("Yükleniyor...", "info", 5500);
        let data = new FormData();
        request("{{API("get_hostname")}}", data, function(response){
            response = JSON.parse(response);
            $('#hostname').text(response.message);
            Swal.close();
        });
    }
    
    function modalCloseClick() {
        $("#getClientWindow").modal("hide");
    }
   
    function showModal() {
        showSwal('{{__("Yükleniyor...")}}','info');
        $('#getClientWindow').modal("show");
        let data = new FormData();
        playbookname = $('#playbookname_field').val();
        data.append('playbookname',playbookname+".yml");
        sudopass = $('#sudopass_field').val();
        data.append('sudopass',sudopass);
        
        request(API("list_hosts"), data, function(response) {
            $("#outTable").html(response).find("table").dataTable(dataTablePresets("normal"));
            Swal.close();
        }, function(response) {
            let error = JSON.parse(response);
            showSwal(error.message, 'error', 3000);
        });
    }

</script>
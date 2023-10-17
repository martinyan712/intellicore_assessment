<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Door') }}
        </h2>
    </x-slot>
    <div class="container bg-body">
    <form class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="{statuses:[{key:1,value:'Active'},{key:0,'value':'Inactive'}],selected_id:{{$code_door|null}},codes:{{$codes}},door:{{$door}}}">
        <div class="form-group">
            <label for="staticEmail" class="col-sm-2 col-form-label">ID</label>
            <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext" id="id" :value="door.id">
            </div>
        </div>
        <div class="form-group">
            <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext" id="staticEmail" :value="door.name">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-form-label" for="code">Code</label>
            <select class="" x-model="selected_id">
            <template x-for="code in codes">
                <option :value="code.id"   x-text="code.code" :selected="code.id == selected_id"></option>
            </template>
        </select>
        <div class="form-group">
            <label class="col-sm-2 col-form-label" for="status">Status</label>
            <select class="" x-model="door.status">
            <template x-for="statusOption in statuses">
                <option :value="statusOption.key"   x-text="statusOption.value" :selected="statusOption.key == door.status"></option>
            </template>
        </select>

        </div>
        <br>
        <div class="form-group">
        <button x-on:click="edit" type="button" class="btn btn-primary">Submit</button>
  <div>
</form>
</div>
</x-app-layout>

<script>
	async function edit(){
        let code =  await(await fetch('/door/edit/'+this.door.id, {
				method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                },
                body:  JSON.stringify({'code':this.selected_id,'status':this.door.status})
            })).json();
        if(code.success){
            alert("Saved");
            location.reload();
        }else{
            alert(code.message);
        }
    }
</script>



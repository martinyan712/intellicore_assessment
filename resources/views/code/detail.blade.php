<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Code') }}
        </h2>
    </x-slot>
    <div class="container bg-body">
    <form class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" x-data="{selected_id:{{$code_door|null}},doors:{{$doors}},code:{{$code}}}">
        <div class="form-group">
            <label for="staticEmail" class="col-sm-2 col-form-label">ID</label>
            <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext" id="id" :value="code.id">
            </div>
        </div>
        <div class="form-group">
            <label for="staticEmail" class="col-sm-2 col-form-label">code</label>
            <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext" id="staticEmail" :value="code.code">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-form-label" for="door">Door</label>
            <select class="" x-model="selected_id">
            <template x-for="door in doors">
                <option :value="door.id"   x-text="door.name" :selected="door.id == selected_id"></option>
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
        let code =  await(await fetch('/code/edit/'+this.code.id, {
				method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                },
                body:  JSON.stringify({'door':this.selected_id})
            })).json();
        if(code.success){
            alert("Saved");
            location.reload();
        }else{
            alert( code.message);
             
        }
    }
</script>



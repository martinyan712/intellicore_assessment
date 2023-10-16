<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Code') }}
        </h2>
    </x-slot>
    <div class="container text-center"  x-data="{ name:'' }">
        <div class="row">
            <div class="col-8 flex items-center gap-4" >
                    <x-input-label for="name" :value="__('Name')"/>
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus autocomplete="name"  x-model="name"/>
                </div>
                <div class="flex items-center gap-4 col-4">
                    <button x-on:click="createDoor" class="btn btn-success" type="button" name="save">{{ __('Create') }}</button>
                        <p
                            x-data="{ doorCreated: false }"
                            x-show="doorCreated"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Created.') }}</p>
                </div>
        </div>
</div>


    <x-widgets.table :url="'/code/list'" :x-ref="'table'">
        <x-slot name="th"> 
            <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Door</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </x-slot>
        <x-slot name="content">
            <tr>
              <th x-text="event.id"></th>
              <th x-text="event.code"></th>
              <th x-text="(event.doors.length>0)?event.doors[0]:''"></th>
              <th x-text="(event.status)?'Active':'InActive'"></th>
              <th><a class="btn btn-primary" x-bind:href="'/code/edit/' + event.id">Edit</a></th>
            </tr>
        </x-slot>
    </x-app-widgets.table>
</x-app-layout>

<script>
    async function createCode(){
        let door =  await(await fetch('/code/create', {
				method: 'POST',
                headers: {
                    'Content-Type': 'application/json; charset=UTF-8',
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                },
                body:  JSON.stringify({'code':this.code})
            })).json();
        if(door.success){
            this.doorCreated = true;
           console.log(window.Alpine.refs);
            alert("Door Created");
        }else{
            alert(door.message.name);
        }
    }
			
</script>



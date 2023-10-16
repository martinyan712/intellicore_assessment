@props(['url'=>''])
<style>
    .form-fields {
  background-color: white;
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: space-evenly;
  border-bottom: #2b2b2b 1px solid;
}

.search {
  min-width: 500px;
}

.result-table {
  min-width: 100%;
}

.result-table th {
  padding: 10px;
  /* border: white 1px solid; */
}

.result-table tr {
  border-bottom: white 0.5px solid;
}

.pagination{
  margin: 20px 0px;
}
</style>

<div x-data="getData()" data-reflect-root="" >
    <form class="form-fields">
      <div class="field search">
        <label class="label" style="display:inline-block;">Search</label>
        <div class="control">
          <input class="input" type="text" placeholder="" x-model="searchValue">
        </div>
      </div>
      <div class="control">
        <Search class="btn btn-primary is-link" @click="fetchData()" :class="[ isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700' ]" :disabled="isLoading" disabled="disabled">
          Search
          </button>
      </div>
    </form>
    <template x-if="events">
      <table class="result-table">
        <thead>
          {{$th}}
        </thead>
        <tbody>
          <template x-for="event in events" :key="event.id">
            {{$content}}
          </template>
        </tbody>
      </table>
      <br />
    </template>
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <a class="pagination-previous" x-model="previousPage" @click="fetchData(previousPage)">Previous</a>
        <a x-show="(lastPage != page)" class="pagination-next" x-model="nextPage" @click="fetchData(nextPage)">Next page</a>
        <ul class="pagination-list">
          <li><a class="pagination-link" x-model="1" x-text="1" @click="fetchData(1)"></a></li>
          <li><span class="pagination-ellipsis">&hellip;</span></li>
          <li><input class="input-page" type="number" placeholder="" x-model="page" x-on:change="fetchData(page)" style="width:100px;"/></li>
          <li><span class="pagination-ellipsis">&hellip;</span></li>
          <li><a class="pagination-link" x-model="lastPage" x-text="lastPage" @click="fetchData(lastPage)"></a></li>
        </ul>
      </nav>
  </div>
  <script type="text/javascript">
    function getData() {
      return {
        selectedOption: "",
        searchValue: '',
        page: 1,
        limit: 10,
        total: null,
        events: null,
        isLoading: false,
        previousPage: 1,
        nextPage: null,
        lastPage: 0,
        fetchData(page = this.page) {
          this.page = page;
          this.isLoading = true;
          fetch(`{{$url}}?search=${this.searchValue}&page=${this.page}&limit=${this.limit}`)
            .then((res) => res.json())
            .then((data) => {
              this.isLoading = false;
              this.events = data.list;
              this.total = data.count;
              this.previousPage = this.page == 1 ? this.previousPage : this.page - 1
              this.nextPage = this.page + 1
              this.lastPage = Math.ceil(this.total / this.limit)
              if(this.page > this.lastPage){
                this.fetchData(this.lastPage);
               
              }
            });
        },
        init(){
          this.page = 1;
          this.isLoading = true;
          fetch(`{{$url}}?search=${this.searchValue}&page=${this.page}&limit=${this.limit}`)
            .then((res) => res.json())
            .then((data) => {
              this.isLoading = false;
              this.events = data.list;
              this.total = data.count;
              this.previousPage = this.page == 1 ? this.previousPage : this.page - 1
              this.nextPage = this.page + 1
              this.lastPage = Math.ceil(this.total / this.limit)
            });
        }
      };
    }
  </script>
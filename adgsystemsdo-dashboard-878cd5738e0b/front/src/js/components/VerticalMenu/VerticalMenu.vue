<template>
  <ul class="list-group">
    <template v-for="item in items">
    <li class="list-group-item" :class="{'activo': item.name == activeItem}" @click="itemClicked(item.name)">
      <div class="arrow-right"></div>
      <span class="menu-icon">
        <i class="fa fa-fw" :class="item.icon"></i>
      </span>
      <div class="text">{{item.text}}</div>
    </li>
    </template>
  </ul>
</template>

<script>
  /*
    Props items: [
      {name,text,icon}
    ]
   */
  export default {
    name:'vertical-menu',
    props: ['items','default'],
    data () {
      return {
        activeItem: null
      }
    },
    methods: {
      itemClicked (name) {
        this.activeItem = name;
        this.$emit('selected',name);
      }
    },
    mounted () {
      if (this.default){
        this.activeItem = this.default;
      }
    }
  }
</script>

<style scoped>

  .list-group-item {
    background-color:#2C3E50 ;
    color: white;
    font-weight: 400;
    border-color: #1c2d3f;
  }

  .list-group-item > .arrow-right {
    width: 0;
    height: 0;
    position: absolute;
    border-top: 10px solid transparent;
    border-bottom: 10px solid transparent;
    border-left: 10px solid #395570;
    left: 42px;
    display: none;
  }

  .list-group-item.activo {
    background-color: #243343;
  }
  .list-group-item.activo > .arrow-right{
    display: block;
  }
  .list-group-item > .menu-icon {
    position: absolute;
    width: 45px;
    height: 40px;
    border-right: 2px solid #395570;
    top:0;
    left:0;
    background-color: #395570;
  }

  .list-group-item:hover {
    cursor: pointer;
    background-color: #243343;
  }
  .list-group-item > .menu-icon > i{
    padding-left: 17px;
    padding-top: 13px;
  }
  .list-group-item > .menu-icon:first-child {
    border-top-left-radius: 4px;
  }

  .list-group-item > .text {
    margin-left:42px;
    white-space: nowrap;
    overflow:hidden;
    text-overflow: ellipsis;
  }
</style>
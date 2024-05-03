<template>
  <q-card bordered :class="'bg-' + color + ' q-ma-xs cursor-pointer'" @click="$router.push(to)">
    <q-card-section class="q-pa-md">
      <div class="row">
        <div class="col-12">
          <q-btn flat dense :label="title" color="white" :icon="icon" :to="to" no-caps />
        </div>
        <div class="col-12 flex flex-center">
          <div class="text-h3 text-white text-bold">
            {{amount}}
            <q-icon :name="icon" size="50px" color="white" />
          </div>
        </div>
      </div>
    </q-card-section>
    <q-tooltip>
        {{title}}
    </q-tooltip>
  </q-card>
</template>
<script>
export default {
  name: 'CardComponent',
  props: {
    title: {
      type: String,
      default: 'Card Title'
    },
    icon: {
      type: String,
      default: 'account_balance'
    },
    to: {
      type: String,
      default: '/'
    },
    color: {
      type: String,
      default: 'green'
    },
    amount: {
      type: Number,
      default: 2000
    }
  }
}
</script>

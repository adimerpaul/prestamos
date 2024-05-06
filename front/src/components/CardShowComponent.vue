<template>
  <q-card class="" flat bordered>
    <q-tooltip anchor="top middle" self="bottom middle">
      {{title}}
    </q-tooltip>
    <q-card-section class="q-pa-none">
      <q-item>
        <q-item-section avatar>
          <q-btn :icon="icono" size="22px" :color="`${color}-7`" :class="`bg-${color}-2`"  flat />
        </q-item-section>
        <q-item-section>
          <q-item-label class="text-subtitle2 text-grey">{{title}}</q-item-label>
          <q-item-label :class="`text-bold text-h6 text-${color=='grey'?'black':color}`">{{monto}} Bs</q-item-label>
        </q-item-section>
      </q-item>
    </q-card-section>
  </q-card>
</template>
<script>
export default {
  name: 'CardShowComponent',
  props: {
    monto: {
      type: Number,
      required: true
    },
    color: {
      type: String,
      default: 'green'
    },
    title: {
      type: String,
      default: 'Ventas'
    },
    icono: {
      type: String,
      default: 'o_trending_up'
    }
  }
}
</script>

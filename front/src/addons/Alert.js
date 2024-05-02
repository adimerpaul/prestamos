import { Notify } from 'quasar'
export class Alert {
  static success (message) {
    Notify.create({
      progress: true,
      color: 'positive',
      position: 'top',
      message,
      // icon: 'check',
      timeout: 1500,
      actions: [{ icon: 'close', color: 'white', size: 'sm' }]
    })
  }

  static error (message) {
    Notify.create({
      progress: true,
      color: 'negative',
      position: 'top',
      message,
      timeout: 1500,
      actions: [{ icon: 'close', color: 'white', size: 'sm' }]
    })
  }

  static warning (message) {
    Notify.create({
      progress: true,
      color: 'warning',
      position: 'top',
      message,
      timeout: 1500,
      actions: [{ icon: 'close', color: 'white', size: 'sm' }]
    })
  }
}

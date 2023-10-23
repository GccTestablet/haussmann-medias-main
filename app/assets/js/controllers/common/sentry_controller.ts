import { Controller } from "@hotwired/stimulus";
import * as Sentry from "@sentry/browser";

export default class extends Controller {
  static values = {
    dsn: String,
    env: String,
  };

  declare readonly dsnValue: string;
  declare readonly envValue: string;

  connect(): void {
    this.init();
  }

  init() {
    Sentry.init({
      dsn: this.dsnValue,
      integrations: [new Sentry.BrowserTracing()],
      environment: this.envValue,
      // Set tracesSampleRate to 1.0 to capture 100%
      // of transactions for performance monitoring.
      // We recommend adjusting this value in production
      tracesSampleRate: 0.1,
    });
  }
}

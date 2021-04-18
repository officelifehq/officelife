import * as Sentry from '@sentry/browser';
import { Vue as VueIntegration } from '@sentry/integrations';
import { Integrations } from '@sentry/tracing';

export default {
  init(app, release) {
    if (typeof SentryConfig !== 'undefined' && SentryConfig.dsn !== '') {
      Sentry.init({
        dsn: SentryConfig.dsn,
        environment: SentryConfig.environment || null,
        release: release || '',
        tracesSampleRate: SentryConfig.tracesSampleRate || 0.0,
        integrations: [
          new VueIntegration({ Vue: app, attachProps: true }),
          SentryConfig.tracesSampleRate > 0 ? new Integrations.BrowserTracing() : null,
        ],
      });
    }
  }
};

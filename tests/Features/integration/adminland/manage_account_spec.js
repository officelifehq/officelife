var faker = require('faker');

describe('Adminland - Basic account management', function () {
  it('should let me access adminland with the right permission level', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]')
        .invoke('attr', 'url').then(function (url) {

        cy.canAccess(url, 100, 'Administration', userId)
        cy.canAccess(url, 200, 'Administration', userId)
        cy.canNotAccess(url, 300, userId)
      })
    })
  })

  it('should let me access audit section only if admin', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]')
        .invoke('attr', 'url').then(function (url) {

        cy.canAccess(url+'/audit', 100, 'Audit logs', userId)
        cy.canNotAccess(url+'/audit', 200, userId)
        cy.canNotAccess(url+'/audit', 300, userId)
      })
    })
  })

  it('should let me access general settings section only if admin', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]')
        .invoke('attr', 'url').then(function (url) {

        cy.canAccess(url+'/general', 100, 'General settings', userId)
        cy.canNotAccess(url+'/general', 200, userId)
        cy.canNotAccess(url+'/general', 300, userId)
      })
    })
  })

  it('should let an administrator update the company name', function () {
    cy.login()

    cy.createCompany((companyId) => {

      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=general-admin-link]').click()

      cy.get('[data-cy=rename-company-button]').click()
      cy.get('[data-cy=cancel-rename-company-button]').click()
      cy.get('[data-cy=rename-company-button]').click()
      cy.get('[data-cy=company-name-input]').clear()

      let name = faker.company.companyName()
      cy.get('[data-cy=company-name-input]').type(name)
      cy.get('[data-cy=submit-rename-company-button]').click()

      cy.get('[data-cy=company-name]').contains(name)

      cy.get('[data-cy=header-adminland-link]')
        .invoke('attr', 'url').then(function (url) {

        cy.hasAuditLog('Renamed the company from', url+'/general', companyId)
      })
    })
  })

  it('should let an administrator update the currency used in the company', function () {
    cy.login()

    cy.createCompany((companyId) => {

      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=general-admin-link]').click()

      cy.get('[data-cy=update-currency-company-button]').click()
      cy.get('[data-cy=cancel-update-currency-company-button]').click()
      cy.get('[data-cy=update-currency-company-button]').click()
      cy.get('[data-cy=currency-selector]').select('EUR')
      cy.get('[data-cy=submit-update-currency-company-button]').click()

      cy.get('[data-cy=currency-used]').contains('EUR')

      cy.get('[data-cy=header-adminland-link]')
        .invoke('attr', 'url').then(function (url) {

        cy.hasAuditLog('Changed the company’s currency from', url+'/general', companyId)
      })
    })
  })
})

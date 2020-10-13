let faker = require('faker');

describe('Adminland - Basic account management', function () {
  it('should let me access adminland with the right permission level', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]', { timeout: 600 }).should('be.visible')
        .invoke('attr', 'href').then(function (url) {

        cy.log(url)

        cy.canAccess(url, 100, 'Administration', userId)
        cy.canAccess(url, 200, 'Administration', userId)
        cy.canNotAccess(url, 300, userId)
      })
    })
  })

  it('should let me access audit section only if admin', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=audit-admin-link]', { timeout: 600 }).should('be.visible')
        .invoke('attr', 'href').then(function (url) {

        cy.canAccess(url, 100, 'Audit logs', userId)
        cy.canNotAccess(url, 200, userId)
        cy.canNotAccess(url, 300, userId)
      })
    })
  })

  it('should let me access general settings section only if admin', function () {
    cy.login((userId) => {

      cy.createCompany()

      cy.get('[data-cy=header-adminland-link]').click()
      cy.get('[data-cy=general-admin-link]', { timeout: 600 }).should('be.visible')
        .invoke('attr', 'href').then(function (url) {

        cy.canAccess(url, 100, 'General settings', userId)
        cy.canNotAccess(url, 200, userId)
        cy.canNotAccess(url, 300, userId)
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

      cy.hasAuditLog('Renamed the company from', null, companyId)
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

      cy.hasAuditLog('Changed the company’s currency from', null, companyId)
    })
  })
})

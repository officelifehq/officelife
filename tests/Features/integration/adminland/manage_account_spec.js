describe('Adminland - Basic account management', function () {
  it('should let me access adminland with the right permission level', function () {
    cy.login2()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]')
      .invoke('attr', 'href').then(function (href) {

      cy.canAccess(href, 100, 'Administration')
      cy.canAccess(href, 200, 'Administration')
      cy.canNotAccess(href, 300)
    })
  })

  it('should let me access audit section only if admin', function () {
    cy.login2()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]')
      .invoke('attr', 'href').then(function (href) {

      cy.canAccess(href+'/audit', 100, 'Audit logs')
      cy.canNotAccess(href+'/audit', 200)
      cy.canNotAccess(href+'/audit', 300)
    })
  })

  it('should let me access general settings section only if admin', function () {
    cy.login2()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]')
      .invoke('attr', 'href').then(function (href) {

      cy.canAccess(href+'/general', 100, 'General settings')
      cy.canNotAccess(href+'/general', 200)
      cy.canNotAccess(href+'/general', 300)
    })
  })

  it('should let an administrator update the company name', function () {
    cy.login2()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=general-admin-link]').click()

    cy.get('[data-cy=rename-company-button]').click()
    cy.get('[data-cy=cancel-rename-company-button]').click()
    cy.get('[data-cy=rename-company-button]').click()
    cy.get('[data-cy=company-name-input]').clear()
    cy.get('[data-cy=company-name-input]').type('Coca Cola')
    cy.get('[data-cy=submit-rename-company-button]').click()

    cy.get('[data-cy=company-name]').contains('Coca Cola')

    cy.get('[data-cy=header-adminland-link]')
      .invoke('attr', 'href').then(function (href) {

      cy.hasAuditLog('Renamed the company from', href+'/general')
    })
  })

  it('should let an administrator update the currency used in the company', function () {
    cy.login2()

    cy.createCompany()

    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=general-admin-link]').click()

    cy.get('[data-cy=update-currency-company-button]').click()
    cy.get('[data-cy=cancel-update-currency-company-button]').click()
    cy.get('[data-cy=update-currency-company-button]').click()
    cy.get('[data-cy=currency-selector]').select('EUR')
    cy.get('[data-cy=submit-update-currency-company-button]').click()

    cy.get('[data-cy=currency-used]').contains('EUR')

    cy.wait(400)

    cy.get('[data-cy=header-adminland-link]')
      .invoke('attr', 'href').then(function (href) {

      cy.hasAuditLog('Changed the company’s currency from', href+'/general')
    })

  })
})

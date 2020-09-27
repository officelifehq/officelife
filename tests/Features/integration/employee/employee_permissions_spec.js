describe('Employee - permissions', function () {
  it('should restrict viewing anyone audit logs depending on role', function () {
    cy.loginLegacy()

    cy.createCompany()

    // create the Admin Michael Scott and visit his profile
    cy.createEmployee('Michael', 'Scott', 'michael.scott@dundermifflin.com', 'admin', true)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=view-log-button]').click()
    cy.url().should('include', '/logs')
    cy.get('body').should('contain', 'Audit log')

    cy.changePermission(1, 200)
    cy.logout()
    cy.visit('/login')
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').click()
    cy.get('[data-cy=view-log-button]').click()
    cy.url().should('include', '/logs')
    cy.get('body').should('contain', 'Audit log')

    cy.changePermission(1, 300)
    cy.logout()
    cy.visit('/login')
    cy.get('input[name=email]').type('admin@admin.com')
    cy.get('input[name=password]').type('admin')
    cy.get('button[type=submit]').click()
    cy.wait(1000)
    cy.visit('/1/employees/2')
    cy.get('[data-cy=edit-profile-button]').should('not.exist')
  })
})

describe('Adminland - Team management', function () {
  it.skip('should create a team', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.contains('product')
  })

  it('should let rename and delete a team as an administrator', function () {
    cy.login()

    cy.createCompany()

    cy.createTeam('product')

    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=team-admin-link]').click()

    cy.get('[data-cy=team-rename-link-1]').click()
    cy.get('[data-cy=list-rename-input-name-1]').clear()
    cy.get('[data-cy=list-rename-input-name-1]').type('sales')
    cy.get('[data-cy=list-rename-cta-button-1]').click()
    cy.get('[data-cy=list-team-1]').contains('sales')

    cy.hasAuditLog('Changed the name of the team from product to sales', '/1/account/teams')

    cy.get('[data-cy=team-destroy-link-1]').click()
    cy.get('[data-cy=list-destroy-cancel-button-1]').click()
    cy.get('[data-cy=team-destroy-link-1]').click()
    cy.get('[data-cy=list-destroy-cta-button-1]').click()
    cy.get('[data-cy=list-team-1]').should('not.exist')

    cy.hasAuditLog('Deleted the team called sales', '/1/account/teams')
  })

  it('should let rename and delete a team as an HR', function () {
    cy.login()

    cy.createCompany()

    cy.changePermission(1, 200)

    cy.createTeam('product')

    cy.get('[data-cy=header-adminland-link]').click()
    cy.get('[data-cy=team-admin-link]').click()

    cy.get('[data-cy=team-rename-link-1]').click()
    cy.get('[data-cy=list-rename-input-name-1]').clear()
    cy.get('[data-cy=list-rename-input-name-1]').type('sales')
    cy.get('[data-cy=list-rename-cta-button-1]').click()
    cy.get('[data-cy=list-team-1]').contains('sales')

    cy.get('[data-cy=team-destroy-link-1]').click()
    cy.get('[data-cy=list-destroy-cancel-button-1]').click()
    cy.get('[data-cy=team-destroy-link-1]').click()
    cy.get('[data-cy=list-destroy-cta-button-1]').click()
    cy.get('[data-cy=list-team-1]').should('not.exist')
  })
})
